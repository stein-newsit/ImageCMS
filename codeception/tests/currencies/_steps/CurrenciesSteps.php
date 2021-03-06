<?php
namespace CurrenciesTester;

class CurrenciesSteps extends \CurrenciesTester
{
    function CreateCurrency($name,$isocode,$symbol,$rate,$save='save')
    {
        $I = $this;
        $I->amOnPage(\CurrenciesPage::$URL);
        $I->click(\CurrenciesPage::$CreateCurrencyButton);
        $I->waitForElement('.//*[@id="mod_name"]/label');
        $I->fillField(\CurrenciesPage::$NameCurrencyCreate, $name);        
        $I->fillField(\CurrenciesPage::$IsoCodCreate, $isocode);
        $I->fillField(\CurrenciesPage::$SymbolCreate, $symbol);
        $I->fillField(\CurrenciesPage::$Rate, $rate);
        $I->dontSeeElement(\CurrenciesPage::$TemplateForm);
        switch ($save) {
            case 'save':
                $I->click(\CurrenciesPage::$SaveButton);
                break;
            case 'saveexit':
                $I->click(\CurrenciesPage::$SaveAndExitButton);
                break;
        }
    }    
    
    function EditCurrency($j,$name=null,$isocode=null,$symbol=null,$rate=null,$template=null,$format=null,$delimTens=null,$delimThousands=null,$amount=null,$notNull='off',$save='save')
    {
        $I = $this;
        $I->amOnPage(\CurrenciesPage::$URL);
        $I->wait("2");
        $I->click(\CurrenciesPage::CurrencyNameLine($j));
        $I->waitForElement('.//*[@id="mod_name"]/label');
        if(isset($name)){
            $I->fillField(\CurrenciesPage::$NameCurrencyEdit, $name);
        }
        if(isset($isocode)){
            $I->fillField(\CurrenciesPage::$IsoCodEdit, $isocode);
        }
        if(isset($symbol)){
            $I->fillField(\CurrenciesPage::$SymbolEdit, $symbol);
        }
        if(isset($rate)){
            $I->fillField(\CurrenciesPage::$Rate, $rate);        
        }
        if(isset($template)){
            $I->selectOption(\CurrenciesPage::$CurrencyTemplate, $template);
            $text=$I->grabTextFrom(\CurrenciesPage::$CurrencyTemplate."/option[$template]");
            $I->comment($text);
        }
        if(isset($format)){
            $I->fillField(\CurrenciesPage::$FormatLine, $format);
        }
//        $I->wait('2');
        if(isset($delimTens)){
            $I->fillField(\CurrenciesPage::$DelimiterTens, $delimTens);
        }
        if(isset($delimThousands)){
            $I->fillField(\CurrenciesPage::$DelimiterThousands, $delimThousands);
        }
        if(isset($amount)){
            $I->click(\CurrenciesPage::$AmountDecimals);
            $I->selectOption(\CurrenciesPage::$AmountDecimals, $amount);
        }
        switch ($notNull) {
            case 'off':
                $I->dontSeeCheckboxIsChecked(\CurrenciesPage::$NotNullsCheckbox);
                break;
            case 'on':
                $I->click(\CurrenciesPage::$NotNullsCheckbox);
                $I->wait('1');
                $I->seeCheckboxIsChecked(\CurrenciesPage::$NotNullsCheckbox);
                break;
        }
        $I->wait('3');
        switch ($save) {
            case 'save':
                $I->click(\CurrenciesPage::$SaveButton);
                break;
            case 'saveexit':
                $I->click(\CurrenciesPage::$SaveAndExitButton);
                break;
        }
    }
    
    function CheckInFields($name1=null,$isocode1=null,$symbol1=null,$rate1=null,$format1=null,$delimTens1=null,$delimThousands1=null,$amount1=null,$notNull1="off")
    {
        $I = $this;
        $I->waitForText('Редактирование валют');
        $I->wait('3');
        $I->seeElement(\CurrenciesPage::$TemplateForm);
        if(isset($name1)){
            $I->seeInField(\CurrenciesPage::$NameCurrencyEdit, $name1);
        }
        if(isset($isocode1)){
            $I->seeInField(\CurrenciesPage::$IsoCodEdit, $isocode1);
        }
        if(isset($symbol1)){
            $I->seeInField(\CurrenciesPage::$SymbolEdit, $symbol1);
        }
        if(isset($rate1)){
            $I->seeInField(\CurrenciesPage::$Rate, $rate1);
        }
        $I->seeOptionIsSelected(\CurrenciesPage::$CurrencyTemplate, 'Не выбрано');
        if(isset($format1)){
            $I->seeInField(\CurrenciesPage::$FormatLine, $format1);
        }
        if(isset($delimTens1)){
            $I->seeInField(\CurrenciesPage::$DelimiterTens, $delimTens1);
        }
        if(isset($delimThousands1)){
            $I->seeInField(\CurrenciesPage::$DelimiterThousands, $delimThousands1);
        }
        if(isset($amount1)){
            $I->seeOptionIsSelected(\CurrenciesPage::$AmountDecimals, $amount1);
        }
        switch ($notNull1) {
            case 'off':
                $I->dontSeeCheckboxIsChecked(\CurrenciesPage::$NotNullsCheckbox);
                break;
            case 'on':
                $I->seeCheckboxIsChecked(\CurrenciesPage::$NotNullsCheckbox);
                break;
        }
    }
    
    function CheckInListLanding($name1,$isocode1,$symbol1)
    {
        $I = $this;
        $I->wait('3');
        $I->see($name1, './/*[@id="mainContent"]/section/div[2]/div/form/table/tbody/tr[last()]/td[2]/a');
        $I->see($isocode1, './/*[@id="mainContent"]/section/div[2]/div/form/table/tbody/tr[last()]/td[3]');
        $I->see($symbol1, './/*[@id="mainContent"]/section/div[2]/div/form/table/tbody/tr[last()]/td[4]');
        $RadioBut=$I->grabAttributeFrom(\CurrenciesPage::RadioButtonLine("last()"), 'checked');
        $I->comment("$RadioBut");
        $I->assertEquals($RadioBut, null);
        $ActiveBut=$I->grabAttributeFrom(\CurrenciesPage::ActiveButtonLine("last()"), 'class');
        $I->comment("$ActiveBut");
        $I->assertEquals($ActiveBut, "prod-on_off disable_tovar");
        $DeleteBut=$I->grabAttributeFrom(\CurrenciesPage::DeleteButtonLine("last()"), 'disabled');
        $I->comment("DeleteBut");
        $I->assertEquals($DeleteBut, null);
    }
    
    function CheckInFrontEnd($name1,$isocode1,$symbol1)
    {
        $I = $this;
        $I->wait('3');
        $I->see($name1, './/*[@id="mainContent"]/section/div[2]/div/form/table/tbody/tr[last()]/td[2]/a');
        $I->see($isocode1, './/*[@id="mainContent"]/section/div[2]/div/form/table/tbody/tr[last()]/td[3]');
        $I->see($symbol1, './/*[@id="mainContent"]/section/div[2]/div/form/table/tbody/tr[last()]/td[4]');
        $RadioBut=$I->grabAttributeFrom(\CurrenciesPage::RadioButtonLine("last()"), 'checked');
        $I->comment("$RadioBut");
        $I->assertEquals($RadioBut, null);
        $ActiveBut=$I->grabAttributeFrom(\CurrenciesPage::ActiveButtonLine("last()"), 'class');
        $I->comment("$ActiveBut");
        $I->assertEquals($ActiveBut, "prod-on_off disable_tovar");
        $DeleteBut=$I->grabAttributeFrom(\CurrenciesPage::DeleteButtonLine("last()"), 'disabled');
        $I->comment("DeleteBut");
        $I->assertEquals($DeleteBut, null);
    }
    
       
    function SearchMainCurrencyLine()
    {
        $I = $this;
        $I->amOnPage(\CurrenciesPage::$URL);
        $rows = $I->grabTagCount($I,"tbody tr");
        $I->comment("$rows");
        //Определение строчки главной валюты
        for ($j=1;$j<$rows;++$j){
            //Поиск атрибута checked для радиоточки
            $atribCheck = $I->grabAttributeFrom(\CurrenciesPage::RadioButtonLine($j),"checked");
                if($atribCheck == TRUE){
                break;
            }
        }
        $I->comment("Main Currency Line: $j");
        return "$j";
    }
    
    
    function CreateProduct($name,$price,$j=null)
    {
        $I = $this;
        $I->amOnPage('/admin/components/run/shop/products/create');
        $I->waitForText('Создание товара');        
        $I->fillField(\ProductsPage::$NameProduct, $name);
        $I->fillField(\ProductsPage::$Price, "$price");
        if(isset($j)){
            $I->click(\ProductsPage::$Currency);
    //        $I->selectOption(\ProductsPage::$Currency, $j);
    //        $I->click(\ProductsPage::$Currency);
            $I->click(\ProductsPage::$Currency."/option[$j]");
            $I->wait('1');
            $IsoProduct=$I->grabTextFrom(\ProductsPage::$Currency."/option[$j]");
            $I->comment("$IsoProduct");
        }
        $I->click(\CurrenciesPage::$SaveButton);
        $I->waitForText($name, 4, ".//*[@id='mainContent']/section/div/div[1]/span[2]");
        if(isset($j)){
            return $IsoProduct;
        }
    }
    
    
    function DeleteWindowOperation($y,$button='delete')
    {
        $I = $this;
        $I->click(\CurrenciesPage::DeleteButtonLine($y));
        $I->waitForElement(\CurrenciesPage::$DeleteWindow);
        $I->see('Удалить валюту');
        $I->see('Удалить выбранную валюту?');
        $I->see('Удалить', \CurrenciesPage::$DeleteButtonWindow);
        $I->see('Отменить', \CurrenciesPage::$CancelButtonWindow);
        switch ($button) {
            case 'delete':
                $I->click(\CurrenciesPage::$DeleteButtonWindow);
                break;
            case 'cancel':
                $I->click(\CurrenciesPage::$CancelButtonWindow);
                break;
        }        
    }
}