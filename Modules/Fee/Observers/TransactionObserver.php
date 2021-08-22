<?php
namespace Modules\Fee\Observers;

use Illuminate\Support\Facades\Log;
use Modules\Fee\Entities\Transaction;
use Modules\Fee\Entities\FeeInvoice;
use Modules\Finance\Entities\FinancialAccount;
use Modules\Finance\Entities\Group;
use Modules\Finance\Entities\Ledger;
use Modules\Finance\Entities\Entries;
use Modules\Finance\Entities\EntriesItem;
class TransactionObserver
{
    private $transaction;
    private $feeInvoice;
    private $financialAccount;
    private $ledger;
    private $group;
    private $entries;
    private $entriesItem;

    public function __construct(Transaction $transaction, FeeInvoice $feeInvoice, FinancialAccount $financialAccount, Ledger $ledger, Group $group, EntriesItem $entriesItem, Entries $entries) {
        $this->transaction       = $transaction;
        $this->feeInvoice       = $feeInvoice;
        $this->financialAccount       = $financialAccount;
        $this->ledger       = $ledger;
        $this->group       = $group;
        $this->entries       = $entries;
        $this->entriesItem       = $entriesItem;
    }

    public function created(Transaction $transaction) {

        Log::info('test observer');
        $creditLedgerId= $transaction->invoiceProfile()->feehead()->ledger_id;

//        Log::info('credit_ledger_id'.$creditLedgerId);
        // get active finacail account id
        $activeAccountId= $this->financialAccount->getActiveAccount();
//        Log::info('Account Id'.$activeAccountId);
        // get fees group
        $groupProfile=  $this->group
                        ->where('account_id',$activeAccountId)
                        ->where('code','FEES')
                        ->first();
            
        Log::info('credit_ledger_id'.$groupProfile);

        $cashLedger =   $this->ledger
                        ->where('group_id',$groupProfile->id)
                        ->where('code','FEES')
                        ->first();

        $debitLedgerId= $cashLedger->id;

        /* insert entry data array to entries table - DB1 */
        $entriesObj=new $this->entries;
        $entriesObj->account_id= $this->financialAccount->getActiveAccount();
        $entriesObj->tag_id='22';
        $entriesObj->number='333';
        $entriesObj->entrytype_id=1;
        $entriesObj->date=$transaction->payment_date;
        $entriesObj->dr_total=$transaction->amount;
        $entriesObj->cr_total=$transaction->amount;
        $entriesObj->notes='Fee Collection';
        $saveEntries=$entriesObj->save();


        // if entry data is inserted
        if ($saveEntries)
        {
            $insert_id = $entriesObj->id; // get inserted entry id

                //create Debit Entries
                $entriesItemObj=new  $this->entriesItem;
                $entriesItemObj->entries_id=$entriesObj->id;
                $entriesItemObj->ledger_id=$debitLedgerId;
                $entriesItemObj->dc='D';
                $entriesItemObj->amount=$transaction->amount;
                $entriesItemObj->narration='Fee Collection';
                $entriesItemObj->save();

                //create Credit Entries
                $entriesItemObj=new  $this->entriesItem;
                $entriesItemObj->entries_id=$entriesObj->id;
                $entriesItemObj->ledger_id=$creditLedgerId;
                $entriesItemObj->dc='C';
                $entriesItemObj->amount=$transaction->amount;
                $entriesItemObj->narration='Fee Collection';
                $entriesItemObj->save();

        }




    }



}
