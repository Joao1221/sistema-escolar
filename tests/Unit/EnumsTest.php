<?php

namespace Tests\Unit;

use App\Enums\StatusMatricula;
use App\Enums\TipoMatricula;
use App\Enums\StatusDemandaPsicossocial;
use App\Enums\StatusAtendimentoPsicossocial;
use App\Enums\TipoPublicoPsicossocial;
use App\Enums\StatusPendenciaDiario;
use PHPUnit\Framework\TestCase;

class EnumsTest extends TestCase
{
    public function test_status_matricula_values(): void
    {
        $this->assertSame('ativa', StatusMatricula::Ativa->value);
        $this->assertSame('concluida', StatusMatricula::Concluida->value);
        $this->assertSame('trancada', StatusMatricula::Trancada->value);
        $this->assertSame('transferida', StatusMatricula::Transferida->value);
        $this->assertSame('cancelada', StatusMatricula::Cancelada->value);
    }

    public function test_status_matricula_labels(): void
    {
        $this->assertSame('Ativa', StatusMatricula::Ativa->label());
        $this->assertSame('Concluída', StatusMatricula::Concluida->label());
        $this->assertSame('Trancada', StatusMatricula::Trancada->label());
        $this->assertSame('Transferida', StatusMatricula::Transferida->label());
        $this->assertSame('Cancelada', StatusMatricula::Cancelada->label());
    }

    public function test_tipo_matricula_values(): void
    {
        $this->assertSame('regular', TipoMatricula::Regular->value);
        $this->assertSame('aee', TipoMatricula::Aee->value);
    }

    public function test_tipo_matricula_labels(): void
    {
        $this->assertSame('Regular', TipoMatricula::Regular->label());
        $this->assertSame('AEE', TipoMatricula::Aee->label());
    }

    public function test_status_demanda_values(): void
    {
        $this->assertSame('aberta', StatusDemandaPsicossocial::Aberta->value);
        $this->assertSame('em_triagem', StatusDemandaPsicossocial::EmTriagem->value);
        $this->assertSame('em_atendimento', StatusDemandaPsicossocial::EmAtendimento->value);
        $this->assertSame('encerrada', StatusDemandaPsicossocial::Encerrada->value);
        $this->assertSame('observacao', StatusDemandaPsicossocial::Observacao->value);
    }

    public function test_status_atendimento_values(): void
    {
        $this->assertSame('aberto', StatusAtendimentoPsicossocial::Aberto->value);
        $this->assertSame('em_atendimento', StatusAtendimentoPsicossocial::EmAtendimento->value);
        $this->assertSame('encerrado', StatusAtendimentoPsicossocial::Encerrado->value);
    }

    public function test_tipo_publico_values(): void
    {
        $this->assertSame('aluno', TipoPublicoPsicossocial::Aluno->value);
        $this->assertSame('professor', TipoPublicoPsicossocial::Professor->value);
        $this->assertSame('funcionario', TipoPublicoPsicossocial::Funcionario->value);
        $this->assertSame('responsavel', TipoPublicoPsicossocial::Responsavel->value);
        $this->assertSame('coletivo', TipoPublicoPsicossocial::Coletivo->value);
    }

    public function test_tipo_publico_labels(): void
    {
        $this->assertSame('Aluno', TipoPublicoPsicossocial::Aluno->label());
        $this->assertSame('Professor', TipoPublicoPsicossocial::Professor->label());
        $this->assertSame('Funcionário', TipoPublicoPsicossocial::Funcionario->label());
        $this->assertSame('Responsável', TipoPublicoPsicossocial::Responsavel->label());
        $this->assertSame('Coletivo', TipoPublicoPsicossocial::Coletivo->label());
    }

    public function test_status_pendencia_values(): void
    {
        $this->assertSame('aberta', StatusPendenciaDiario::Aberta->value);
        $this->assertSame('em_andamento', StatusPendenciaDiario::EmAndamento->value);
        $this->assertSame('resolvida', StatusPendenciaDiario::Resolvida->value);
    }

    public function test_enum_can_be_created_from_string(): void
    {
        $status = StatusMatricula::from('ativa');
        $this->assertSame(StatusMatricula::Ativa, $status);

        $tipo = TipoMatricula::from('aee');
        $this->assertSame(TipoMatricula::Aee, $tipo);

        $demanda = StatusDemandaPsicossocial::from('aberta');
        $this->assertSame(StatusDemandaPsicossocial::Aberta, $demanda);

        $publico = TipoPublicoPsicossocial::from('coletivo');
        $this->assertSame(TipoPublicoPsicossocial::Coletivo, $publico);
    }

    public function test_all_enums_implement_label_method(): void
    {
        foreach (StatusMatricula::cases() as $c) {
            $this->assertNotEmpty($c->label());
        }
        foreach (TipoMatricula::cases() as $c) {
            $this->assertNotEmpty($c->label());
        }
        foreach (StatusDemandaPsicossocial::cases() as $c) {
            $this->assertNotEmpty($c->label());
        }
        foreach (StatusAtendimentoPsicossocial::cases() as $c) {
            $this->assertNotEmpty($c->label());
        }
        foreach (TipoPublicoPsicossocial::cases() as $c) {
            $this->assertNotEmpty($c->label());
        }
        foreach (StatusPendenciaDiario::cases() as $c) {
            $this->assertNotEmpty($c->label());
        }
    }
}