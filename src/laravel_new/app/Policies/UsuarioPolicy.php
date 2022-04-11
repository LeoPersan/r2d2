<?php

namespace App\Policies;

use App\Models\Usuario;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsuarioPolicy
{
    use HandlesAuthorization;

    public function viewAny(Usuario $user)
    {
        return in_array('Admin', $user->permissoes);
    }

    public function view(Usuario $user, Usuario $usuario)
    {
        return in_array('Admin', $user->permissoes);
    }

    public function create(Usuario $user)
    {
        return in_array('Admin', $user->permissoes);
    }

    public function update(Usuario $user, Usuario $usuario)
    {
        return in_array('Admin', $user->permissoes);
    }

    public function delete(Usuario $user, Usuario $usuario)
    {
        return in_array('Admin', $user->permissoes);
    }

    public function alterarPermissoes(Usuario $user)
    {
        return in_array('Admin', $user->permissoes);
    }

    public function restore(Usuario $user, Usuario $usuario)
    {
        return in_array('Admin', $user->permissoes);
    }

    public function forceDelete(Usuario $user, Usuario $usuario)
    {
        return in_array('Admin', $user->permissoes);
    }
}
