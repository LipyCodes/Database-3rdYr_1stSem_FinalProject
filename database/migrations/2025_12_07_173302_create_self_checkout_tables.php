<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table: Categories [cite: 43]
        Schema::create('categories', function (Blueprint $table) {
            $table->id('CategoryID'); // PK
            $table->string('CategoryName');
            $table->text('Description')->nullable();
            $table->timestamps();
        });

        // Table: Customers [cite: 21, 32]
        Schema::create('customers', function (Blueprint $table) {
            $table->id('CustomerID'); // PK
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('Email')->unique();
            $table->string('Phone'); // Handled as string to prevent int overflow
            $table->text('Address');
            $table->dateTime('CreatedAt')->useCurrent();
            $table->timestamps();
        });

        // Table: Products [cite: 27, 40]
        Schema::create('products', function (Blueprint $table) {
            $table->id('ProductID'); // PK
            $table->unsignedBigInteger('CategoryID'); // FK
            $table->string('Name');
            $table->text('Description')->nullable();
            $table->decimal('Price', 10, 2);
            $table->integer('StockQuantity');
            $table->timestamps();

            $table->foreign('CategoryID')->references('CategoryID')->on('categories')->onDelete('cascade');
        });

        // Table: Orders [cite: 23, 36]
        Schema::create('orders', function (Blueprint $table) {
            $table->id('OrderID'); // PK
            $table->unsignedBigInteger('CustomerID'); // FK
            $table->dateTime('OrderDate')->useCurrent();
            $table->enum('Status', ['Pending', 'Completed', 'Cancelled'])->default('Pending');
            $table->decimal('TotalAmount', 10, 2);
            $table->timestamps();

            $table->foreign('CustomerID')->references('CustomerID')->on('customers')->onDelete('cascade');
        });

        // Table: OrderItems [cite: 26, 42]
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('OrderItemID'); // PK
            $table->unsignedBigInteger('OrderID'); // FK
            $table->unsignedBigInteger('ProductID'); // FK
            $table->integer('Quantity');
            $table->decimal('UnitPrice', 10, 2); // Price snapshot
            $table->timestamps();

            $table->foreign('OrderID')->references('OrderID')->on('orders')->onDelete('cascade');
            $table->foreign('ProductID')->references('ProductID')->on('products')->onDelete('cascade');
        });

        // Table: Payments [cite: 24, 38]
        Schema::create('payments', function (Blueprint $table) {
            $table->id('PaymentID'); // PK
            $table->unsignedBigInteger('OrderID'); // FK
            $table->dateTime('PaymentDate')->useCurrent();
            $table->decimal('Amount', 10, 2);
            $table->string('PaymentMethod'); // e.g., Cash, Card
            $table->string('PaymentStatus'); // e.g., Success, Failed
            $table->timestamps();

            $table->foreign('OrderID')->references('OrderID')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('categories');
    }
};