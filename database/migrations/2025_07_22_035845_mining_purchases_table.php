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
            Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('requested_by');
            $table->string('purchase_order_number')->unique();
            $table->string('item_name');
            $table->string('item_code')->unique();
            $table->string('category');
            $table->integer('quantity');
            $table->string('unit');
            $table->decimal('buy_price', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 15, 2);
            $table->decimal('tax', 15, 2);
            $table->decimal('grand_total', 15, 2);
            $table->dateTime('purchase_date');
            $table->date('expected_delivery_date');
            $table->enum('status', ['Pending', 'Approved', 'Delivered'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('pembelian');
        }
    };
