<?php

use App\Models\Subscription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index()->nullable();
            $table->date('first_payment_date')->index();
            $table->date('next_payment_date')->index()->nullable();
            $table->unsignedInteger('interval_value')->index();
            $table->enum('interval_type', [
                Subscription::DAY_INTERVAL,
                Subscription::WEEK_INTERVAL,
                Subscription::MONTH_INTERVAL,
                Subscription::YEAR_INTERVAL,
            ])
                ->index();
            $table->float('payment_amount')->index();
            $table->string('currency_code')->index();
            $table->string('image')->nullable();
            $table->foreignId('service_id')->constrained('services')->restrictOnDelete();
            $table->boolean('with_prolongation')->default(true);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
