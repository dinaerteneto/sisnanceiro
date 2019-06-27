<?php

namespace Sisnanceiro\Services;

use Illuminate\Support\Facades\DB;

class SynchronizerService extends Service
{

    private function person()
    {
        $sourceIds = [];

        $lastSource = DB::connection('mysql')
            ->table('person')
            ->whereNotNull('source_id')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->first();
        $lastSourceId = count($lastSource) > 0 ? $lastSource->source_id : 0;

        $sourcePeople = DB::connection('mysql-sulbahia-prod')
            ->table('pessoa')
            ->where('pess_pess', '=', 957)
            ->where('pess_codi', '>', $lastSourceId)
            ->get();
        if ($sourcePeople) {
            foreach ($sourcePeople as $sourcePeople) {
                // $iPos      = strpos($sourceCustomer->pess_nome, ' ');
                // $firstname = substr($sourceCustomer->pess_nome, 0, $iPos);
                // $lastname  = substr($sourceCustomer->pess_nome, $iPos, strlen($sourceCustomer->pess_nome));

                DB::connection('mysql')
                    ->table('person')
                    ->insert([
                        'source_id'    => $sourcePeople->pess_codi,
                        'company_id'   => env('SULBAHIA_ID'),
                        'physical'     => !$sourcePeople->pess_juri ? 1 : 0,
                        'status'       => $sourcePeople->pess_stat,
                        'firstname'    => $sourcePeople->pess_nome,
                        // 'lastname'     => $lastname,
                        'cpf'          => $sourcePeople->pess_doc,
                        'company_name' => $sourcePeople->pess_raso,
                        'created_at'   => $sourcePeople->pess_dins,
                    ]);

                $sourceIds[] = $sourcePeople->pess_codi;
            }
            return $sourceIds;
        }
    }

    private function user()
    {
        $sourceIds = [];

        $lastSource = DB::connection('mysql')
            ->table('users')
            ->whereNotNull('source_id')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->first();
        $lastSourceId = count($lastSource) > 0 ? $lastSource->source_id : 0;

        $sourceUsers = DB::connection('mysql-sulbahia-prod')
            ->table('usuario')
            ->where('usua_codi', '>', $lastSourceId)
            ->where('usua_pess', '=', 957)
            ->get();

        if ($sourceUsers) {
            foreach ($sourceUsers as $sourceUser) {
                $person = DB::connection('mysql')
                    ->table('person')
                    ->where('source_id', '=', $sourceUser->usua_codi)
                    ->first();

                if ($person) {
                    DB::connection('mysql')
                        ->table('users')
                        ->insert([
                            'source_id'  => $sourceUser->usua_codi,
                            'id'         => $person->id,
                            'company_id' => env('SULBAHIA_ID'),
                            'email'      => $sourceUser->usua_logi,
                            'password'   => bcrypt('123456'),
                        ]);
                    $sourceIds[] = $sourceUser->usua_codi;
                }
            }
        }
        return $sourceIds;
    }

    private function customer()
    {
        $sourceIds = [];

        $lastSource = DB::connection('mysql')
            ->table('customer')
            ->whereNotNull('source_id')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->first();
        $lastSourceId = count($lastSource) > 0 ? $lastSource->source_id : 0;

        $sourceCustomers = DB::connection('mysql-sulbahia-prod')
            ->table('pessoa_cliente')
            ->where('pecl_pess', '>', $lastSourceId)
            ->get();

        if ($sourceCustomers) {
            foreach ($sourceCustomers as $sourceCustomer) {
                $person = DB::connection('mysql')
                    ->table('person')
                    ->where('source_id', '=', $sourceCustomer->pecl_pess)
                    ->first();
                if ($person) {
                    DB::connection('mysql')
                        ->table('customer')
                        ->insert([
                            'source_id' => $sourceCustomer->pecl_pess,
                            'id'        => $person->id,
                        ]);
                    $sourceIds[] = $sourceCustomer->pecl_pess;
                }
            }
        }
        return $sourceIds;
    }

    private function supplier()
    {
        $sourceIds = [];

        $lastSource = DB::connection('mysql')
            ->table('supplier')
            ->whereNotNull('source_id')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->first();
        $lastSourceId = count($lastSource) > 0 ? $lastSource->source_id : 0;

        $sourceSuppliers = DB::connection('mysql-sulbahia-prod')
            ->table('pessoa_fornecedor')
            ->where('pefo_pess', '>', $lastSourceId)
            ->get();

        if ($sourceSuppliers) {
            foreach ($sourceSuppliers as $sourceSupplier) {
                $person = DB::connection('mysql')
                    ->table('person')
                    ->where('source_id', '=', $sourceSupplier->pefo_pess)
                    ->first();
                if ($person) {
                    DB::connection('mysql')
                        ->table('supplier')
                        ->insert([
                            'source_id' => $sourceSupplier->pefo_pess,
                            'id'        => $person->id,
                        ]);
                    $sourceIds[] = $sourceSupplier->pefo_pess;
                }
            }
        }
        return $sourceIds;
    }

    private function personAddress()
    {
        $sourceIds = [];

        $lastSource = DB::connection('mysql')
            ->table('person_address')
            ->whereNotNull('source_id')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->first();
        $lastSourceId = count($lastSource) > 0 ? $lastSource->source_id : 0;

        $sourceAddresses = DB::connection('mysql-sulbahia-prod')
            ->table('pessoa_endereco')
            ->where('peen_codi', '>', $lastSourceId)
            ->get();
        if ($sourceAddresses) {
            foreach ($sourceAddresses as $sourceAddress) {
                $person = DB::connection('mysql')
                    ->table('person')
                    ->where('source_id', '=', $sourceAddress->peen_pess)
                    ->first();

                if ($person) {
                    DB::connection('mysql')
                        ->table('person_address')
                        ->insert([
                            'source_id'              => $sourceAddress->peen_codi,
                            'person_id'              => $person->id,
                            'company_id'             => env('SULBAHIA_ID'),
                            'person_address_type_id' => $sourceAddress->peen_pent,
                            'zip_code'               => $sourceAddress->peen_cep,
                            'address'                => $sourceAddress->peen_ende,
                            'number'                 => $sourceAddress->peen_nume,
                            'complement'             => $sourceAddress->peen_comp,
                            'reference'              => $sourceAddress->peen_refe,
                            'city'                   => $sourceAddress->peen_cida,
                            'district'               => $sourceAddress->peen_bair,
                            'uf'                     => $sourceAddress->peen_uf,
                        ]);
                    $sourceIds[] = $sourceAddress->peen_codi;
                }
            }
        }

        return $sourceIds;
    }

    private function personContact()
    {
        $sourceIds = [];

        $lastSource = DB::connection('mysql')
            ->table('person_contact')
            ->whereNotNull('source_id')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->first();
        $lastSourceId = count($lastSource) > 0 ? $lastSource->source_id : 0;

        $sourceContacts = DB::connection('mysql-sulbahia-prod')
            ->table('pessoa_contato')
            ->where('peco_codi', '>', $lastSourceId)
            ->get();
        if ($sourceContacts) {
            foreach ($sourceContacts as $sourceContact) {
                $person = DB::connection('mysql')
                    ->table('person')
                    ->where('source_id', '=', $sourceContact->peco_pess)
                    ->first();
                if ($person) {
                    DB::connection('mysql')
                        ->table('person_contact')
                        ->insert([
                            'source_id'              => $sourceContact->peco_codi,
                            'person_id'              => $person->id,
                            'company_id'             => env('SULBAHIA_ID'),
                            'person_contact_type_id' => $sourceContact->peco_pect,
                            'value'                  => $sourceContact->peco_desc,
                        ]);
                    $sourceIds[] = $sourceContact->peco_codi;
                }
            }
        }
        return $sourceIds;
    }

    private function product()
    {
        $sourceIds = [];

        $lastSource = DB::connection('mysql')
            ->table('store_product')
            ->whereNotNull('source_id')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->first();
        $lastSourceId = count($lastSource) > 0 ? $lastSource->source_id : 0;

        $sourceProducts = DB::connection('mysql-sulbahia-prod')
            ->table('produto')
            ->where('prod_codi', '>', $lastSourceId)
            ->where('prod_pess', '=', 957)
            ->get();
        if ($sourceProducts) {
            foreach ($sourceProducts as $sourceProduct) {
                DB::connection('mysql')
                    ->table('store_product')
                    ->insert([
                        'source_id'        => $sourceProduct->prod_codi,
                        'company_id'       => env('SULBAHIA_ID'),
                        'name'             => $sourceProduct->prod_titu,
                        'description'      => $sourceProduct->prod_desc,
                        'sku'              => $sourceProduct->prod_codi_manu,
                        'price'            => is_numeric($sourceProduct->prod_valo_vare) ? $sourceProduct->prod_valo_vare : null,
                        'unit_measurement' => strtoupper($sourceProduct->prod_umed),
                        'weight'           => is_numeric($sourceProduct->prod_peso) ? $sourceProduct->prod_peso : null,
                        'created_at'       => $sourceProduct->prod_dins,
                        'deleted_at'       => $sourceProduct->prod_ddel,
                    ]);
                $sourceIds[] = $sourceProduct->prod_codi;
            }
            return $sourceIds;
        }

    }

    private function sale()
    {
        $sourceIds = [];

        $lastSource = DB::connection('mysql')
            ->table('sale')
            ->whereNotNull('source_id')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->first();
        $lastSourceId = count($lastSource) > 0 ? $lastSource->source_id : 0;

        $sourceSales = DB::connection('mysql-sulbahia-prod')
            ->table('venda')
            ->where('vend_codi', '>', $lastSourceId)
            ->where('vend_pess', '=', 957)
            ->limit(100)
            ->get();
        if ($sourceSales) {
            foreach ($sourceSales as $sourceSale) {
                $customer = DB::connection('mysql')
                    ->table('person')
                    ->where('source_id', '=', $sourceSale->vend_pess)
                    ->first();
                $user = DB::connection('mysql')
                    ->table('person')
                    ->where('source_id', '=', $sourceSale->vend_usua)
                    ->first();
                $sale = DB::connection('mysql')
                    ->table('sale')
                    ->insert([
                        'source_id'         => $sourceSale->vend_codi,
                        'company_id'        => env('SULBAHIA_ID'),
                        'customer_id'       => $customer->id,
                        'user_id_created'   => $user->id,
                        'company_sale_code' => $sourceSale->vend_codi_manu,
                        'status'            => $sourceSale->vend_vest,
                        'gross_value'       => $sourceSale->vend_valo,
                        'discount_value'    => $sourceSale->vend_vdes,
                        'net_value'         => $sourceSale->vend_valo,
                        'sale_date'         => $sourceSale->vend_dins,
                        'created_at'        => $sourceSale->vend_dins,
                    ]);
                $sourceIds[] = $sourceSale->vend_codi;

                $items = DB::connection('mysql-sulbahia-prod')
                    ->table('venda_produto')
                    ->where('vepr_vend', '=', $sourceSale->vend_codi)
                    ->get();
                if ($items) {
                    foreach ($items as $item) {
                        $sale = DB::connection('mysql')
                            ->table('sale')
                            ->orderBy('id', 'desc')
                            ->limit(1)
                            ->first();

                        $product = DB::connection('mysql')
                            ->table('store_product')
                            ->where('source_id', '=', $item->vepr_prod)
                            ->first();

                        DB::connection('mysql')
                            ->table('sale_item')
                            ->insert([
                                'company_id'       => env('SULBAHIA_ID'),
                                'sale_id'          => $sale->id,
                                'store_product_id' => $product->id,
                                'unit_value'       => $item->vepr_valo / $item->vepr_quan,
                                'quantity'         => $item->vepr_quan,
                                'total_value'      => $item->vepr_valo,
                            ]);
                    }
                }
            }
        }
        return $sourceIds;
    }

    public function sync()
    {
        $this->person();
        $this->user();
        $this->customer();
        $this->supplier();
        $this->personAddress();
        $this->personContact();
        $this->product();
        $this->sale();
    }

}
