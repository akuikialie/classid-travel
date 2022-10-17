<?php

use App\Models\Plan\Plan;
use Illuminate\Support\Str;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->definesTable();
        $this->addressTable();
        $this->emailsTable();
        $this->phonesTable();

        $this->trySeed();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('defines');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('emails');
        Schema::dropIfExists('phones');
        Schema::enableForeignKeyConstraints();
    }

    private function definesTable(): void
    {
        Schema::create('defines', function (Blueprint $table) {
            $table->id('id');
            $table->string('type', 50)->default('global');
            $table->string('key');
            $table->text('value')->nullable()->comment('main value');
            $table->jsonb('options')->nullable()->comment('optional information');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['type', 'key']);
        });

        // $this->defineSeed();
    }

    private function addressTable(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->boolean('is_primary')->default(false);
            $table->string('name')->default('lokasi');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('rt_rw')->nullable();
            $table->string('sub_district')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->index(['addresses_type', 'addresses_id']);
        });
    }

    private function emailsTable(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id('id');
            $table->string('model_type');
            $table->string('model_id');
            $table->boolean('is_primary')->default(false);
            $table->string('name')->default('Email Pribadi');
            $table->string('email');
            $table->timestamps();
            $table->softDeletes();

            // $table->index(['emails_type', 'emails_id']);
        });
    }

    private function phonesTable(): void
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id('id');
            $table->string('model_type');
            $table->string('model_id');
            $table->boolean('is_primary')->default(false);
            $table->string('name')->default('Mobile');
            $table->string('phone_code', 5)->default('62');
            $table->string('phone_number');
            $table->timestamps();
            $table->softDeletes();

            // $table->index(['phones_type', 'phones_id']);
        });
    }

    public function trySeed(): void
    {
        // try {
        //     collect([
        //         'Umrah', 'Haji', 'Wisata',
        //     ])->each(fn ($cat, $i) => Plan::create([
        //         'type' => 'plan',
        //         'key' => Str::slug($cat),
        //         'value' => $cat,
        //         'order' => $i
        //     ]));
        // } catch (\Throwable $th) {
        //     throw $th;
        // }
    }

    // private function defineSeed(): void
    // {
    //     try {
    //         collect([
    //             [
    //                 'type' => 'global',
    //                 'key' => 'logo',
    //                 'value' => 'https://danamu.co.id/assets/front/assets/images/logonew-1.png',
    //             ],
    //         ])->each(fn($def) => \CID\DanaMu\Models\Base\Define::create($def));

    //         // categories
    //         collect([
    //             'Perkebunan', 'Peternakan', 'Pertanian', 'Konstruksi', 'Manufaktur',
    //             'Pendidikan', 'Sosial', 'Lembaga SDM'
    //         ])->each(fn($cat, $i) => \CID\DanaMu\Models\Base\Define::create([
    //             'type' => 'category',
    //             'key' => \Yusronarif\Core\Support\Str::slug($cat),
    //             'value' => $cat,
    //             'order' => $i
    //         ]));

    //         // user documents
    //         collect([
    //             'Kartu Identitas::1'
    //         ])->each(function ($doc, $i) {
    //             [$val, $req] = explode('::', $doc, 2);
    //             \CID\DanaMu\Models\Base\Define::create([
    //                 'type' => 'userDocument',
    //                 'key' => \Yusronarif\Core\Support\Str::slug($val),
    //                 'value' => $val,
    //                 'is_required' => (bool) $req,
    //                 'order' => $i,
    //             ]);
    //         });

    //         // project documents
    //         collect([
    //             'Proposal Proyek::1'
    //         ])->each(function ($doc, $i) {
    //             [$val, $req] = explode('::', $doc, 2);
    //             \CID\DanaMu\Models\Base\Define::create([
    //                 'type' => 'projectDocument',
    //                 'key' => \Yusronarif\Core\Support\Str::slug($val),
    //                 'value' => $val,
    //                 'is_required' => (bool) $req,
    //                 'order' => $i,
    //             ]);
    //         });

    //     } catch (\Exception $e) {
    //         throw_if(debugNonProduction(), $e);
    //     }
    // }
};
