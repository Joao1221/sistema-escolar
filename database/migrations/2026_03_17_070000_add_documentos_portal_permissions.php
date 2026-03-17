<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permissoes = [
            'consultar documentos escolares',
            'emitir declaracao de matricula',
            'emitir declaracao de frequencia',
            'emitir comprovante de matricula',
            'emitir ficha cadastral do aluno',
            'emitir ficha individual do aluno',
            'emitir guia de transferencia',
            'emitir historico escolar',
            'emitir ata escolar',
            'emitir oficio escolar',
            'consultar documentos institucionais da rede',
            'emitir oficio institucional da rede',
            'emitir modelo institucional da rede',
            'consultar documentos da direcao escolar',
            'emitir documentos da direcao escolar',
            'consultar documentos pedagogicos',
            'emitir documentos pedagogicos',
            'consultar documentos do professor',
            'emitir documentos do professor',
            'consultar documentos psicossociais',
            'emitir documentos psicossociais',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Secretário Escolar', 'web')->givePermissionTo([
            'consultar documentos escolares',
            'emitir declaracao de matricula',
            'emitir declaracao de frequencia',
            'emitir comprovante de matricula',
            'emitir ficha cadastral do aluno',
            'emitir ficha individual do aluno',
            'emitir guia de transferencia',
            'emitir historico escolar',
            'emitir ata escolar',
            'emitir oficio escolar',
        ]);

        Role::findOrCreate('Administrador da Rede', 'web')->givePermissionTo([
            'consultar documentos institucionais da rede',
            'emitir oficio institucional da rede',
            'emitir modelo institucional da rede',
        ]);

        Role::findOrCreate('Diretor Escolar', 'web')->givePermissionTo([
            'consultar documentos da direcao escolar',
            'emitir documentos da direcao escolar',
        ]);

        Role::findOrCreate('Coordenador Pedagógico', 'web')->givePermissionTo([
            'consultar documentos pedagogicos',
            'emitir documentos pedagogicos',
        ]);

        Role::findOrCreate('Professor', 'web')->givePermissionTo([
            'consultar documentos do professor',
            'emitir documentos do professor',
        ]);

        Role::findOrCreate('Psicologia/Psicopedagogia', 'web')->givePermissionTo([
            'consultar documentos psicossociais',
            'emitir documentos psicossociais',
        ]);
    }

    public function down(): void
    {
        $permissoes = [
            'consultar documentos escolares',
            'emitir declaracao de matricula',
            'emitir declaracao de frequencia',
            'emitir comprovante de matricula',
            'emitir ficha cadastral do aluno',
            'emitir ficha individual do aluno',
            'emitir guia de transferencia',
            'emitir historico escolar',
            'emitir ata escolar',
            'emitir oficio escolar',
            'consultar documentos institucionais da rede',
            'emitir oficio institucional da rede',
            'emitir modelo institucional da rede',
            'consultar documentos da direcao escolar',
            'emitir documentos da direcao escolar',
            'consultar documentos pedagogicos',
            'emitir documentos pedagogicos',
            'consultar documentos do professor',
            'emitir documentos do professor',
            'consultar documentos psicossociais',
            'emitir documentos psicossociais',
        ];

        foreach ($permissoes as $permissao) {
            $registro = Permission::where('name', $permissao)->where('guard_name', 'web')->first();

            if ($registro) {
                $registro->delete();
            }
        }
    }
};
