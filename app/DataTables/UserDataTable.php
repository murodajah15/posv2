<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $cmenu = $this->submenu1;
        $session = session();
        $username = $session->get('email');
        $level = $session->get('level');
        $pakai = 0;
        $tambah = 0;
        $edit = 0;
        $hapus = 0;
        $proses = 0;
        $unproses = 0;
        $cetak = 0;
        if ($level == 'ADMINISTRATOR') {
            $pakai = 1;
            $tambah = 1;
            $edit = 1;
            $hapus = 1;
            $proses = 1;
            $unproses = 1;
            $cetak = 1;
        } else {
            // if (isset($userdtl->pakai)) {
            //     $pakai = $userdtl->pakai;
            //     $tambah = $userdtl->tambah;
            //     $edit = $userdtl->edit;
            //     $hapus = $userdtl->hapus;
            //     $proses = $userdtl->proses;
            //     $unproses = $userdtl->unproses;
            //     $cetak = $userdtl->cetak;
            // }
        }
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) use ($edit) {
                return "<a href='#{$row->id}'><button onclick='edit({$row->id})' class='btn btn-sm btn-warning' href='javascript:void(0)'> <i class='fa fa-edit'></i></button></a>";
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->orderable(false)->searchable(false)->width(20),
            // Column::make('id'),
            Column::make('email'),
            Column::make('email'),
            Column::make('username'),
            // Column::make('created_at'),
            // Column::make('updated_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
