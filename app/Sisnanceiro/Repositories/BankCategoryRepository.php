<?php
namespace Sisnanceiro\Repositories;

use Sisnanceiro\Models\BankCategory;

class BankCategoryRepository extends Repository
{

 public function __construct(BankCategory $model)
 {
  $this->model = $model;

 }

 /**
  * return all categories with active status
  * @param integer $mainParentCategoryId
  * @return Collection
  */
 public function getCategories($mainParentCategoryId)
 {

  $categories = BankCategory::select('id', 'main_parent_category_id', 'parent_category_id', 'name as text', 'can_delete')
   ->where('status', '=', BankCategory::STATUS_ACTIVE)
   ->where('id', '>', 1)
   ->orWhere('company_id', 'is', null)
   ->orderBy('parent_category_id', 'id');
  if ($mainParentCategoryId) {
   $categories->where('main_parent_category_id', '=', $mainParentCategoryId);
  }
  if ($categories) {
   return $categories->get();
  }
  return [];
 }

 public function addDefaultCategories()
 {
  $categories = [
   'to_receive' => [
    'Salário',
    'Free-Lancers',
    'Presentes',
    'Bônus',
    'PLR (Participação nos lucros)',
    'Restituição do Imposto de renda',
    'Pensão alimentícia',
    'Extras',
    'Pró-labore',
   ],
   'to_pay'     => [
    'Aplicações (10%)'    => [
     'Renda fixa',
     'Renda variável',
     'Reserva de emergência (6 meses de contas pagas)',
    ],
    'Sonhos (10%)'        => [
     'Viagens',
     'Faculdade',
     'Carro',
     'Imóvel',
    ],
    'Diversão (10%)'      => [
     'Cinema',
     'Restaurantes',
     'Presentes',
     'Passeios',
     'Vídeo game',
    ],
    'Doações (5%)'        => [
     'Dízimo',
     'Instituição de caridade',
    ],
    'Custo de vida (55%)' => [
     'Educação do(s) filho(s)',
     'Convênio médico',
     'Internet',
     'Celular',
     'Tel. fixo',
     'Tv à cabo',
     'Faculdade',
     'Prestação habitacional',
     'Impostos',
     'Alimentação',
     'Refeiçao',
     'Mercado',
     'Feira',
     'Roupas',
     'Aluguel',
     'Papelaria',
     'Material escolar',
     'Água',
     'Luz',
     'Gás',
     'Condomínio',
     'Pensão alimentícia',
     'Farmácia',
    ],
    'Educação (10%)'      => [
     'Cursos',
     'Livros',
     'Palestras',
     'Workshops',
     'Mentorias',
    ],
   ],
  ];

  $companyId = \Auth::user()->company_id;

  try {
   \DB::beginTransaction();

   $mainToReceive = $this->findBy('main_parent_category_id', BankCategory::CATEGORY_TO_RECEIVE);
   $mainToPay     = $this->findBy('main_parent_category_id', BankCategory::CATEGORY_TO_PAY);

   if (!$mainToReceive) {
    foreach ($categories['to_receive'] as $toReceive) {
     $data = [
      'company_id'              => $companyId,
      'main_parent_category_id' => BankCategory::CATEGORY_TO_RECEIVE,
      'parent_category_id'      => BankCategory::CATEGORY_TO_RECEIVE,
      'status'                  => BankCategory::STATUS_ACTIVE,
      'name'                    => $toReceive,
      'can_delete'              => 1,
     ];
     BankCategory::create($data);
    }
   }

   if (!$mainToPay) {
    foreach ($categories['to_pay'] as $key => $toPay) {
     $data = [
      'company_id'              => $companyId,
      'main_parent_category_id' => BankCategory::CATEGORY_TO_PAY,
      'parent_category_id'      => BankCategory::CATEGORY_TO_PAY,
      'status'                  => BankCategory::STATUS_ACTIVE,
      'name'                    => $key,
      'can_delete'              => 0,
     ];
     $newCategory = BankCategory::create($data);

     foreach ($toPay as $keyB => $subCategory) {
      $data = [
       'company_id'              => $companyId,
       'main_parent_category_id' => BankCategory::CATEGORY_TO_PAY,
       'parent_category_id'      => $newCategory->id,
       'status'                  => BankCategory::STATUS_ACTIVE,
       'name'                    => $subCategory,
       'can_delete'              => 1,
      ];
      BankCategory::create($data);
     }
    }
   }
   \DB::commit();

   return true;
  } catch (\Exception $e) {
   \DB::rollback();
   abort($e->getMessage());
  }

 }

}
