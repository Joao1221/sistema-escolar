<?php

namespace Tests\Unit;

use App\Enums\StatusMatricula;
use App\Enums\TipoMatricula;
use PHPUnit\Framework\TestCase;

class MatriculaServiceTest extends TestCase
{
    public function test_status_matricula_enum_is_backed(): void
    {
        $this->assertSame('ativa', StatusMatricula::Ativa->value);
        $this->assertInstanceOf(\BackedEnum::class, StatusMatricula::Ativa);
    }

    public function test_tipo_matricula_enum_is_backed(): void
    {
        $this->assertSame('regular', TipoMatricula::Regular->value);
        $this->assertSame('aee', TipoMatricula::Aee->value);
        $this->assertCount(2, TipoMatricula::cases());
    }

    public function test_status_matricula_all_cases(): void
    {
        $cases = StatusMatricula::cases();
        $this->assertCount(5, $cases);
        $values = array_map(fn ($c) => $c->value, $cases);
        $this->assertContains('ativa', $values);
        $this->assertContains('concluida', $values);
        $this->assertContains('trancada', $values);
        $this->assertContains('transferida', $values);
        $this->assertContains('cancelada', $values);
    }

    public function test_tipo_publico_all_cases(): void
    {
        $cases = \App\Enums\TipoPublicoPsicossocial::cases();
        $this->assertCount(5, $cases);
        $values = array_map(fn ($c) => $c->value, $cases);
        $this->assertContains('aluno', $values);
        $this->assertContains('professor', $values);
        $this->assertContains('funcionario', $values);
        $this->assertContains('responsavel', $values);
        $this->assertContains('coletivo', $values);
    }

    public function test_status_demanda_all_cases(): void
    {
        $cases = \App\Enums\StatusDemandaPsicossocial::cases();
        $this->assertCount(5, $cases);
        $values = array_map(fn ($c) => $c->value, $cases);
        $this->assertContains('aberta', $values);
        $this->assertContains('em_triagem', $values);
        $this->assertContains('em_atendimento', $values);
        $this->assertContains('encerrada', $values);
        $this->assertContains('observacao', $values);
    }
}