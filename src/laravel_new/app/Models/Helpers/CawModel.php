<?php

namespace App\Models\Helpers;

use Exception;
use Google_Client;
use Google_Service_Drive;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CawModel extends Model
{
    use HasFactory;

    //caso não queira manter o log, informar false!
    protected $log = true;

    const ORIENTACOES = ['portrait', 'landscape'];
    const IDIOMAS = ['pt_BR', 'en', 'es'];

    public function __construct(array $attributes = [])
    {

        parent::__construct($attributes);

        if(empty($attributes)) {
            //inicializa todos os atrubutos como null
            foreach ($this->fillable as $item) {
                $this->setAttribute($item, null);
            }
        }
    }

    public function scopeBloqueados(Builder $query)
    {
        return $query->whereBloqueado(1);
    }

    public function scopeNaoBloqueados(Builder $query)
    {
        return $query->whereBloqueado(0);
    }

    public function save(array $options = [])
    {
        if(!parent::save($options) || $this->log === false || (!$this->wasChanged() && !$this->wasRecentlyCreated))
            return false;

        try {
            DB::beginTransaction();
            CawLog::create([
                'tablename' => $this->getTable(),
                'id_table' => $this->getOriginal($this->primaryKey),
                'dados' => json_encode(array_merge($this->getChanges() ?? $this->getAttributes(), [
                    'user' => auth('admin')->user() ?? auth('api')->user(),
                ])),
                'data' => date('Y-m-d H:i:s'),
                'ip' => \request()->ip(),
            ]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(date('d/m/Y : H:i:s')." - Erro ao inserir no log - [".$e->getMessage()."] [".$e->getFile()."] [".$e->getLine()."]");
            return false;
        }
    }

    public function delete()
    {
        if(!parent::delete() || $this->log === false)
            return false;

        try {
            DB::beginTransaction();
            CawLog::create([
                'tablename' => $this->table,
                'id_table' => $this->getOriginal($this->primaryKey),
                'dados' => json_encode([
                    'deleted_at' => date('Y-m-d H:i:s'),
                    'user' => auth('admin')->user() ?? auth('api')->user(),
                ]),
                'data' => date('Y-m-d H:i:s'),
                'ip' => \request()->ip(),
            ]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(date('d/m/Y : H:i:s')." - Erro ao inserir no log - [".$e->getMessage()."] [".$e->getFile()."] [".$e->getLine()."]");
            return false;
        }
    }
}
