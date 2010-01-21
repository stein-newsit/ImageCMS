<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends Controller {

    private $temp_cats = array();

	function __construct()
	{
		parent::Controller();

		$this->load->library('DX_Auth');
		if( $this->dx_auth->is_admin() == FALSE)
		{
			redirect('admin/login','');
		}

		$this->load->library('lib_admin');
		$this->load->library('lib_category');
		$this->lib_admin->init_settings();
	}

	function index()
	{
		//code
	}

    // Display create category form
	function create_form()
    {
 		$this->template->assign('tree', $this->lib_category->build());
		$this->template->assign('parent_id', NULL);
		$this->template->assign('include_cats', $this->sub_cats( $this->lib_category->build() ) );

		$this->template->show('create_cat', FALSE);
	}

    // Refresh categoies in sidebar
	function update_block()
	{
		$this->template->assign('tree',$this->lib_category->build());
		$this->template->show('cats_sidebar', FALSE);
	}

    function cat_list()
    {
        $cats = array();

        $tree = $this->lib_category->build();

        $cats = $this->sub_cats($tree);

        // Get total pages in category
        $cnt = count($cats);
        for ($i = 0; $i < $cnt; $i++) 
        {
            $this->db->where('category', $cats[$i]['id']);
            $this->db->where('lang_alias', 0);
            $this->db->from('content');
            $cats[$i]['pages'] = $this->db->count_all_results();
        }

        $this->template->add_array(array(
                'tree' => $cats
            ));

        $this->template->show('category_list', FALSE);
    }

    function sub_cats($array = array())
    {
        foreach ($array as $item)
        {
            $this->temp_cats[] = $item;

            if ( count($item['subtree']) > 0 )
            {
                $this->sub_cats( $item['subtree'] );
            }
        }

        return $this->temp_cats;
    }

	/*
	 * Create or update new category
	 *
	 * @access public
	 */
	function create($action, $cat_id = 0)
    {
		$this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[160]');
		$this->form_validation->set_rules('url', 'URL Категории', 'trim|min_length[2]|max_length[300]|alpha_dash');
		$this->form_validation->set_rules('image', 'Изображение', 'max_length[250]');
		$this->form_validation->set_rules('position', 'Позиция', 'required|integer|max_length[11]');
		$this->form_validation->set_rules('parent_id', 'Родитель', 'trim|required|integer|max_length[160]');
		$this->form_validation->set_rules('description', 'Описание', 'trim');
		$this->form_validation->set_rules('keywords', 'Ключевые слова', 'trim');
		$this->form_validation->set_rules('short_desc', 'Краткое описание', 'trim');
		$this->form_validation->set_rules('title', 'Title', 'trim|max_length[250]');
		$this->form_validation->set_rules('tpl', 'Шаблон', 'trim|max_length[50]');
		$this->form_validation->set_rules('page_tpl', 'Шаблон Страниц', 'trim|max_length[50]');
		$this->form_validation->set_rules('main_tpl', 'Главный Шаблон', 'trim|max_length[50]');
		$this->form_validation->set_rules('per_page', '', 'required|trim|integer|max_length[9]|min_length[1]|is_natural_no_zero');

		if ($this->form_validation->run() == FALSE)
		{
			showMessage (validation_errors());
		}
        else
        {
            // Create category URL
            if ($this->input->post('url') == FALSE)
            {
                $this->load->helper('translit');
                $url = translit_url($this->input->post('name'));
            }else{
                $url = $this->input->post('url');
            }

            $fetch_pages = $this->input->post('fetch_pages');

            if (count($fetch_pages) > 0)
            {
                $fetch_pages = serialize($fetch_pages);
            }

			$data = array(
                'name' => $this->input->post('name'),
                'url' => $url,
                'image' => $this->lib_admin->db_post('image'),
                'position' => $this->input->post('position'),
                'short_desc' => $this->lib_admin->db_post('short_desc'),
                'parent_id' => $this->input->post('parent_id'),
                'description' => $this->lib_admin->db_post('description'),
                'keywords' => $this->lib_admin->db_post('keywords'),
                'title' => $this->lib_admin->db_post('title'),
                'tpl' => $this->lib_admin->db_post('tpl'),
                'main_tpl' => $this->lib_admin->db_post('main_tpl'),
                'page_tpl' => $this->lib_admin->db_post('page_tpl'),
                'per_page' => $this->lib_admin->db_post('per_page'),
                'order_by' => $this->lib_admin->db_post('order_by'),
                'sort_order' => $this->lib_admin->db_post('sort_order'),
                'comments_default' => $this->lib_admin->db_post('comments_default'),
                'fetch_pages' => $fetch_pages,
			);

                $parent = $this->lib_category->get_category($data['parent_id']);

                if ($parent != 'NULL')
                {
                    $full_path = $parent['path_url'].$data['url'].'/';
                }
                else
                {
                    $full_path = $data['url'].'/';
                }

				if ( ($this->category_exists($full_path) == TRUE) AND ($action != 'update') AND ($data['url'] != 'core') )
				{
                    $data['url'] .= time();
				}

                switch ($action)
                {
                    case 'new':
                        $id = $this->cms_admin->create_category($data);

                        $this->lib_admin->log('
                        Создал категорию   
                        <a href="#" onclick="edit_category('.$id.'); return false;">'.$data['name'].'</a>'
                        );

                        showMessage ('Категория '.$data['name'].' создана.');

                        updateDiv('page', site_url('admin/categories/edit/'.$id));
                    break;

                    case 'update':
                        $this->cms_admin->update_category($data, $cat_id);
                        $this->lib_category->clear_cache();
                        $this->lib_category->build();

                        // update pages path
                        $new_path = $this->lib_category->GetValue($this->input->post('cat_id'),'path_url');
                        $this->db->where('category',$this->input->post('cat_id'));
                        $this->db->update('content',array('cat_url' => $new_path));
                        //end update pages

                        $this->lib_admin->log('
                        Изменил категорию   
                        <a href="#" onclick="edit_category('.$cat_id.'); return false;">'.$data['name'].'</a>'
                        );

                       showMessage('Категория обновлена'); 
                    break;
                }

                $this->lib_category->clear_cache();
                updateDiv('categories', site_url('/admin/categories/update_block')); // Update categories on workspace
		}

	}

    function category_exists($str)
    {
        return $this->lib_category->get_category_by('path_url', $str);
    }

    function fast_add($action = '')
    {
        $this->template->add_array(array(
            'tree' => $this->lib_category->build(), 
        ));

        if ($action == '')
        {
            $this->template->show('fast_cat_add', FALSE);
        }
        
        if ($action == 'create')
        {
            $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[160]');
            $this->form_validation->set_rules('parent_id', 'Родитель', 'trim|required|integer|max_length[160]');
        
            if ($this->form_validation->run() == FALSE)
            {
                showMessage (validation_errors());
            }
            else
            {
                // Create category URL
                if ($this->input->post('url') == FALSE)
                {
                    $this->load->helper('translit');
                    $url = translit_url($this->input->post('name'));
                }else{
                    $url = $this->input->post('url');
                }

                $fetch_pages = '';

                $data = array(
                    'name' => $this->input->post('name'),
                    'url' => $url,
                    'position' => '0',
                    'short_desc' => '',
                    'parent_id' => $this->input->post('parent_id'),
                    'description' => '',
                    'keywords' => '',
                    'title' => '',
                    'tpl' => '',
                    'main_tpl' => '',
                    'page_tpl' => '',
                    'per_page' => 15,
                    'order_by' => 'publish_date',
                    'sort_order' => 'desc',
                    'comments_default' => '1',
                    'fetch_pages' => $fetch_pages,
                );

                $parent = $this->lib_category->get_category($data['parent_id']);

                if ($parent != 'NULL')
                {
                    $full_path = $parent['path_url'].$data['url'].'/';
                }
                else
                {
                    $full_path = $data['url'].'/';
                }

                if ( ($this->category_exists($full_path) == TRUE) AND ($action != 'update') AND ($data['url'] != 'core') )
                {
                    $data['url'] .= time();
                }

                $id = $this->cms_admin->create_category($data);
                $this->lib_category->clear_cache();

                $this->lib_admin->log('
                Создал категорию   
                <a href="#" onclick="edit_category('.$id.'); return false;">'.$data['name'].'</a>'
                );

                updateDiv('categories', site_url('/admin/categories/update_block'));
                updateDiv('fast_category_list', site_url('/admin/categories/update_fast_block/'.$id));
                closeWindow('fast_add_cat_w');
                jsCode("$('comments_status').checked = true;");
            }
        }
    }

    function update_fast_block($sel_id)
    {
        $this->template->add_array(array(
            'tree' => $this->lib_category->build(),
            'sel_cat' => $sel_id,
        ));

        echo 'Категория: <select name="category" ONCHANGE="change_comments_status();" id="category_selectbox">
                <option value="0" selected="selected">Нет</option>';
        
        $this->template->show('cats_select', FALSE);

        echo "</select>";
    }
    

	/**
	 * Show edit category window
	 *
	 * @access public
	 */
	function edit($id)
	{
		$cat = $this->cms_admin->get_category($id);

		if ($cat !== FALSE)
		{
            // Get langs
            $langs = $this->cms_base->get_langs();

            $this->template->assign('langs', $langs);

            $cat['fetch_pages'] = unserialize($cat['fetch_pages']);
			$this->template->add_array($cat);
			$this->template->assign('tree', $this->lib_category->build());
            $this->template->assign('include_cats', $this->sub_cats( $this->lib_category->build() ) );
			$this->template->show('category_edit', FALSE);
		}else{
			return FALSE;
		}

	}

    function translate($id, $lang)
    {
	    $cat = $this->cms_admin->get_category($id); 

        if (count($_POST) > 0)
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('name', 'Название', 'trim|required|min_length[1]|max_length[160]');
            $this->form_validation->set_rules('image', 'Изображение', 'max_length[250]');
            $this->form_validation->set_rules('description', 'Описание', 'trim');
            $this->form_validation->set_rules('keywords', 'Ключевые слова', 'trim');
            $this->form_validation->set_rules('short_desc', 'Краткое описание', 'trim');
            $this->form_validation->set_rules('title', 'Meta Title', 'trim|max_length[250]');

	        if ($this->form_validation->run() == FALSE)
		    {
		    	showMessage (validation_errors());
		    }
            else
            {
                $data = array();
                $data['alias'] = $id;
                $data['lang'] = $lang;
                $data['name'] = $this->input->post('name');
                $data['image'] = $_POST['image'];
                $data['description'] = $_POST['description'];
                $data['keywords'] = $_POST['keywords'];
                $data['short_desc'] = $_POST['short_desc'];
                $data['title'] = $_POST['title'];
                
                $this->db->where('alias', $id);
                $this->db->where('lang', $lang);
                $query = $this->db->get('category_translate');

                if ($query->num_rows() == 0)
                {
                    $this->lib_admin->log('
                    Создал перевод категории 
                    <a href="#" onclick="edit_category('.$cat['id'].'); return false;">'.$cat['name'].'</a>'
                    );

                    $this->db->insert('category_translate', $data);
                }
                else
                {
                    $this->lib_admin->log('
                    Изменил перевод категории 
                    <a href="#" onclick="edit_category('.$cat['id'].'); return false;">'.$cat['name'].'</a>'
                    );

                    $this->db->where('alias', $id);
                    $this->db->where('lang', $lang);
                    $this->db->update('category_translate', $data);
                }

                $this->lib_category->clear_cache();
                closeWindow('translate_category_w');
            }

            exit;
        }

		if ($cat !== FALSE)
		{
            // Get translated category
            $this->db->where('alias', $id);
            $this->db->where('lang', $lang);
            $query = $this->db->get('category_translate');

            if ($query->num_rows() > 0)
            {
                $this->template->add_array(array(
                        'cat' => $query->row_array(),
                    ));
            }

            $this->template->add_array(array(
                        'orig_cat' => $cat,
                        'lang' => $lang,
                    ));

            $this->template->show('cat_translate', FALSE);               		
        }
        else
        {
			return FALSE;
		}
    }

	/**
	 * Delete category and its pages
     *
     * TODO: delete sub cats and pages
     *
	 * @param integer $cat_id
	 * @access public
	 */
	function delete()
    {
        $cat_id = $this->input->post('id');

		// Delete Category
		$this->db->limit(1);
		$this->db->where('id',$cat_id);
		$this->db->delete('category');

        $this->lib_admin->log('Удалил категорию ID '.$cat_id);

        // Delete translates
        $this->db->where('alias', $cat_id);
        $this->db->delete('category_translate');

		// Delete pages
		$this->db->where('category',$cat_id);
		$pages = $this->db->get('content');

		if ($pages->num_rows() > 0)
		{
			$this->load->module('admin/pages','pages');
			foreach($pages->result_array() as $page)
			{
				$this->pages->delete($page['id'], FALSE);
			}
		}

		$this->lib_category->clear_cache();
		showMessage('Категория удалена!');
	
		return TRUE;
    }

    function get_comments_status($id)
    {
        $this->db->select('comments_default');
        $this->db->where('id', $id);
        $query = $this->db->get('category')->row_array();

        echo json_encode($query);
    }

}

/* End of categories.php */
