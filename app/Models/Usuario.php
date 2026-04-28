<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, MustVerifyEmailTrait, Notifiable, HasRoles, HasPermissions;

    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ativo',
        'funcionario_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function escolas()
    {
        return $this->belongsToMany(Escola::class, 'usuarios_escolas', 'usuario_id', 'escola_id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }

    /**
     * Retorna o ID da primeira escola vinculada ao usuário.
     * Utilizado para contexto operacional no Portal da Secretaria Escolar.
     * 
     * @return int|null
     */
    public function getEscolaIdAttribute()
    {
        /** @var \App\Models\Escola|null $escola */
        $escola = $this->escolas()->first();
        return $escola ? $escola->id : null;
    }

    public function resolverFuncionario(): ?Funcionario
    {
        if ($this->relationLoaded('funcionario') && $this->funcionario) {
            return $this->funcionario;
        }

        if ($this->funcionario_id) {
            return $this->funcionario()->first();
        }

        return Funcionario::query()
            ->where('email', $this->email)
            ->first();
    }

    public function acessaPortalPsicossocial(): bool
    {
        return $this->hasRole('Psicologia/Psicopedagogia');
    }

    public function possuiAcessoIrrestritoPsicossocial(): bool
    {
        return $this->acessaPortalPsicossocial()
            && $this->can('acesso irrestrito psicossocial');
    }
}
