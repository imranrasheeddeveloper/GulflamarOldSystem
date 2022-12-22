<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\expenseItem;

class CreateExpenseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->timestamps();
        });

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Labor Service Monthly';
        $expenseItem->name_ar = 'خدمة العمال شهري';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Labor Service Daily';
        $expenseItem->name_ar = 'خدمة العمال اليومية';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Labor Service Hourly';
        $expenseItem->name_ar = 'خدمة العمال في الساعة';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Deduction Days';
        $expenseItem->name_ar = 'أيام الخصم';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Deduction Hours';
        $expenseItem->name_ar = 'ساعات الخصم';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Overtime Days';
        $expenseItem->name_ar = 'أيام العمل الإضافي';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Overtime Hours';
        $expenseItem->name_ar = 'ساعات العمل الإضافي';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Deposit for Service';
        $expenseItem->name_ar = 'إيداع للخدمة';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Cleaning Labor Service Monthly';
        $expenseItem->name_ar = 'خدمة العمال نظافة شهري';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Cleaning Labor Service Daily';
        $expenseItem->name_ar = 'خدمة العمال نظافة اليومية';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Cleaning Labor Service Hourly';
        $expenseItem->name_ar = 'خدمة العمال نظافة في الساعة';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Accommodation Service';
        $expenseItem->name_ar = 'خدمة السكن';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Transportation Service';
        $expenseItem->name_ar = 'خدمة مواصلات';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Other Service';
        $expenseItem->name_ar = 'خدمة أخرى';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Other Deductions';
        $expenseItem->name_ar = 'خصومات أخرى';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Disinfection Service Visit';
        $expenseItem->name_ar = 'زيارة خدمة التعقيم';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Disinfection Service Monthly';
        $expenseItem->name_ar = 'خدمة التعقيم شهري';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Support Services (Male)';
        $expenseItem->name_ar = 'خدمات الدعم - رجال';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Support Services (Female)';
        $expenseItem->name_ar = 'خدمات الدعم - نساء';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Pest Control Service';
        $expenseItem->name_ar = 'خدمة مكافحة الحشرات';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Pest Control Visit';
        $expenseItem->name_ar = 'زيارة مكافحة الحشرات';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Loading/Unloading/Sorting Staff';
        $expenseItem->name_ar = 'خدمة عمال تحمبل / تفريغ';
        $expenseItem->save();

        $expenseItem = new expenseItem;
        $expenseItem->name = 'Polishing Service (Floor/Other)';
        $expenseItem->name_ar = 'خدمة تلميع - أرضية / أخرى';
        $expenseItem->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_items');
    }
}
