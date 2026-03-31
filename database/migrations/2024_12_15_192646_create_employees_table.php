<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('emp_code');
        $table->unsignedBigInteger('emp_type');
        $table->unsignedBigInteger('emp_appellation');
        $table->string('emp_first_name');
        $table->string('emp_middle_name')->nullable();
        $table->string('emp_last_name')->nullable();
        $table->unsignedBigInteger('emp_designation')->nullable();
        $table->date('emp_dob');
        $table->unsignedBigInteger('emp_sex');
        $table->date('emp_date_of_joining');
        $table->string('emp_aadhar')->nullable(); // Change to string for Aadhar numbers
        $table->string('emp_pan')->nullable(); // Change to string for PAN numbers
        $table->unsignedBigInteger('emp_appointed_organisation')->nullable();
        $table->string('emp_contact_no'); // Change to string for phone numbers
        $table->string('emp_emergency_contact_no')->nullable(); // Change to string for emergency numbers
        $table->string('emp_udin')->nullable();
        $table->text('emp_address')->nullable();
        $table->tinyInteger('emp_status');
        $table->timestamps();

        // Foreign key relationships
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('emp_type')->references('id')->on('emptypes')->onDelete('cascade');
        $table->foreign('emp_appellation')->references('id')->on('appellations')->onDelete('cascade');
        $table->foreign('emp_designation')->references('id')->on('designations')->onDelete('cascade');
        $table->foreign('emp_sex')->references('id')->on('genders')->onDelete('cascade');
        $table->foreign('emp_appointed_organisation')->references('id')->on('appointedorganizations')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
