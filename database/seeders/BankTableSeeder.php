<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;


class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks =[
            [
              'name_en'=>'Saudi National Bank',
              'name_ar'=>'البنك الأهلي السعودي',
              'full_name'=>'Saudi National Bank - البنك الأهلي السعودي',

            ],
            [
                'name_en'=>'The Saudi British Bank (SABB)',
                'name_ar'=>'البنك السعودي البريطاني (ساب))',
                'full_name'=>'The Saudi British Bank (SABB) - البنك السعودي البريطاني (ساب))',
  
            ],
            [
                'name_en'=>'Saudi Investment Bank',
                'name_ar'=>'البنك السعودي للاستثمار',
                'full_name'=>'Saudi Investment Bank - البنك السعودي للاستثمار',
  
            ],
            [
                'name_en'=>'Alinma bank',
                'name_ar'=>'مصرف الإنماء',
                'full_name'=>'Alinma bank - مصرف الإنماء',
  
            ],
            [
                'name_en'=>'Banque Saudi Fransi',
                'name_ar'=>'البنك السعودي الفرنسي',
                'full_name'=>'Banque Saudi Fransi - البنك السعودي الفرنسي',
  
            ],
            [
                'name_en'=>'Riyad Bank',
                'name_ar'=>'بنك الرياض',
                'full_name'=>'Riyad Bank - بنك الرياض',
  
            ],
            [
                'name_en'=>'Al Rajhi Bank',
                'name_ar'=>'مصرف الراجحي',
                'full_name'=>'Al Rajhi Bank - مصرف الراجحي',
  
            ],
            [
                'name_en'=>'Arab National Bank',
                'name_ar'=>'البنك العربي الوطني',
                'full_name'=>'Arab National Bank - البنك العربي الوطني',
  
            ],
            [
                'name_en'=>'Bank AlBilad',
                'name_ar'=>'بنك البلاد',
                'full_name'=>'Bank AlBilad - بنك البلاد',
  
            ],
            [
                'name_en'=>'Bank AlJazira',
                'name_ar'=>'بنك الجزيرة',
                'full_name'=>'Bank AlJazira - بنك الجزيرة',
  
            ],
            [
                'name_en'=>'Gulf International Bank Saudi Arabia (GIB-SA)',
                'name_ar'=>'بنك الخليج الدولي - السعودية',
                'full_name'=>'Gulf International Bank Saudi Arabia (GIB-SA) - بنك الخليج الدولي - السعودية',
  
            ],
            [
                'name_en'=>'Emirates NBD',
                'name_ar'=>'بنك الإمارات دبي الوطني',
                'full_name'=>'Emirates NBD - بنك الإمارات دبي الوطني',
  
            ],
            [
                'name_en'=>'National Bank of Bahrain (NBB)',
                'name_ar'=>'بنك البحرين الوطني',
                'full_name'=>'National Bank of Bahrain (NBB) - بنك البحرين الوطني',
  
            ],
            [
                'name_en'=>'National Bank of Kuwait (NBK)',
                'name_ar'=>'بنك الكويت الوطني',
                'full_name'=>'National Bank of Kuwait (NBK) - بنك الكويت الوطني',
  
            ],
            [
                'name_en'=>'Bank Muscat',
                'name_ar'=>'بنك مسقط',
                'full_name'=>'Bank Muscat - بنك مسقط',
  
            ],
            [
                'name_en'=>'Deutsche Bank',
                'name_ar'=>'البنك الألماني',
                'full_name'=>'Deutsche Bank - البنك الألماني',
  
            ],
            [
                'name_en'=>'BNP Paribas',
                'name_ar'=>'مصرف باريس الوطني باريباس',
                'full_name'=>'BNP Paribas - مصرف باريس الوطني باريباس',
  
            ],
            [
                'name_en'=>'J.P. Morgan Chase N.A',
                'name_ar'=>'جي بي مورغان تشيس',
                'full_name'=>'J.P. Morgan Chase N.A - جي بي مورغان تشيس',
  
            ],
            [
                'name_en'=>'National Bank of Pakistan (NBP)',
                'name_ar'=>'البنك الوطني الباكستاني',
                'full_name'=>'National Bank of Pakistan (NBP) - البنك الوطني الباكستاني',
  
            ],
            [
                'name_en'=>'State Bank of India (SBI)',
                'name_ar'=>'بنك الدولة الهندي',
                'full_name'=>'State Bank of India (SBI) - بنك الدولة الهندي',
  
            ],
            [
                'name_en'=>'T.C.ZIRAAT BANKASI A.S.',
                'name_ar'=>'تي سي زيرات بنكسي أ.',
                'full_name'=>'T.C.ZIRAAT BANKASI A.S. - تي سي زيرات بنكسي أ.',
  
            ],
            [
                'name_en'=>'Industrial and Commercial Bank of China (ICBC)',
                'name_ar'=>'البنك الصناعي والتجاري الصيني',
                'full_name'=>'Industrial and Commercial Bank of China (ICBC) - البنك الصناعي والتجاري الصيني',
  
            ],
            [
                'name_en'=>'Qatar National Bank (QNB)',
                'name_ar'=>'بنك قطر الوطني',
                'full_name'=>'Qatar National Bank (QNB) - بنك قطر الوطني',
  
            ],
            [
                'name_en'=>'MUFG Bank, Ltd.',
                'name_ar'=>'بنك إم يو إف جي المحدود.',
                'full_name'=>'MUFG Bank, Ltd. - بنك إم يو إف جي المحدود.',
  
            ],
            [
                'name_en'=>'First Abu Dhabi Bank (FAB)',
                'name_ar'=>'بنك أبوظبي الأول',
                'full_name'=>'First Abu Dhabi Bank (FAB) - بنك أبوظبي الأول',
  
            ],
            [
                'name_en'=>'Trade bank of Iraq ',
                'name_ar'=>'المصرف التجاري العراقي',
                'full_name'=>'Trade bank of Iraq - المصرف التجاري العراقي',
  
            ],
            [
                'name_en'=>'Standard Chartered Bank',
                'name_ar'=>'بنك ستاندرد تشارترد',
                'full_name'=>'Standard Chartered Bank - بنك ستاندرد تشارترد',
  
            ],
            [
                'name_en'=>'Credit Suisse',
                'name_ar'=>'كريدي سويس',
                'full_name'=>'Credit Suisse - كريدي سويس',
  
            ],
            
        ];
        Bank::insert($banks);
    
    }
}
