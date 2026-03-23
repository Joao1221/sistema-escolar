<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatorio de sessoes - {{ $atendimento->nome_atendido }}</title>
    <style>
        body { font-family: 'Manrope', system-ui, -apple-system, sans-serif; margin: 0; padding: 32px; color: #0f172a; background: #f8fafc; }
        h1,h2,h3 { margin: 0 0 12px 0; }
        .header, .section { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 20px; margin-bottom: 18px; box-shadow: 0 10px 30px rgba(15,23,42,0.08);}
        .grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(240px,1fr)); gap: 12px; }
        .label { font-size: 11px; letter-spacing: .08em; text-transform: uppercase; color: #64748b; font-weight: 700; }
        .value { margin-top: 4px; font-weight: 600; }
        .session { border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px; background: #fdfefe; }
        .session h3 { margin-bottom: 6px; }
        .muted { color: #94a3b8; font-size: 12px; }
        .row { margin-top: 6px; }
        .row .label { display: block; }
        .row .value { white-space: pre-line; }
        .pill { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .pill.blue { background: #e0f2fe; color: #0369a1; }
        .pill.green { background: #dcfce7; color: #166534; }
        @page { size: A4; margin: 15mm; }
        @media print {
            body { background: #fff; padding: 0; font-size: 12px; line-height: 1.4; }
            h1 { font-size: 18px; }
            h2 { font-size: 16px; }
            h3 { font-size: 14px; }
            .header, .section { box-shadow: none; border-radius: 8px; border: 1px solid #cbd5e1; margin-bottom: 12px; padding: 16px; }
            .grid { gap: 8px; }
            .label { font-size: 9px; letter-spacing: .06em; }
            .value { font-size: 12px; }
            .session { padding: 10px; border-radius: 10px; page-break-inside: avoid; break-inside: avoid; }
            .muted { font-size: 11px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="flex" style="gap:14px; align-items:center;">
            @php
                $brasao = $instituicao?->brasao_url;
            @endphp
            <div style="width:70px;height:70px; border-radius:14px; overflow:hidden; background:#f1f5f9; display:flex; align-items:center; justify-content:center;">
                @if($brasao)
                    <img src="{{ $brasao }}" alt="Brasao" style="max-width:100%; max-height:100%; object-fit:contain;">
                @else
                    <span class="muted" style="font-size:11px; text-align:center;">Brasao</span>
                @endif
            </div>
            <div style="flex:1;">
                <p class="label" style="margin:0;">{{ $instituicao->nome_prefeitura ?? 'PREFEITURA MUNICIPAL' }}</p>
                <p class="value" style="margin-top:6px; font-size:18px;">{{ $instituicao->nome_secretaria ?? 'Secretaria de Educacao' }}</p>
                <p class="value" style="margin-top:2px; font-size:16px; font-weight:700;">Relatorio de atendimento</p>
            </div>
        </div>
        <div class="grid" style="margin-top:18px;">
            <div>
                <span class="label">Paciente</span>
                <div class="value">{{ $atendimento->nome_atendido }}</div>
            </div>
            <div>
                <span class="label">Escola</span>
                <div class="value">{{ $atendimento->escola?->nome }}</div>
            </div>
            <div>
                <span class="label">Responsavel tecnico</span>
                <div class="value">{{ $atendimento->profissionalResponsavel?->nome ?? 'Nao informado' }}</div>
            </div>
            <div>
                <span class="label">Status</span>
                <div class="value">{{ ucfirst(str_replace('_',' ',$atendimento->status)) }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Sessoes realizadas</h2>
        @forelse ($atendimento->sessoes as $sessao)
            <div class="session">
                <div class="grid">
                    <div>
                        <span class="label">Data</span>
                        <div class="value">{{ $sessao->data_sessao->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <span class="label">Tipo</span>
                        <div class="value">{{ ucfirst($sessao->tipo_sessao) }}</div>
                    </div>
                    <div>
                        <span class="label">Status</span>
                        <div class="value">
                            <span class="pill {{ $sessao->status === 'realizado' ? 'green' : 'blue' }}">{{ ucfirst($sessao->status) }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="label">Hora</span>
                        <div class="value">{{ $sessao->hora_inicio ? $sessao->hora_inicio.' - '.$sessao->hora_fim : 'Nao informado' }}</div>
                    </div>
                </div>
                <div class="grid row">
                    <div>
                        <span class="label">Objetivo</span>
                        <div class="value">{{ $sessao->objetivo_sessao ?: 'Nao informado' }}</div>
                    </div>
                    <div>
                        <span class="label">Relato</span>
                        <div class="value">{{ $sessao->relato_sessao ?: 'Nao informado' }}</div>
                    </div>
                    <div>
                        <span class="label">Estrategias</span>
                        <div class="value">{{ $sessao->estrategias_utilizadas ?: 'Nao informado' }}</div>
                    </div>
                    <div>
                        <span class="label">Comportamento observado</span>
                        <div class="value">{{ $sessao->comportamento_observado ?: 'Nao informado' }}</div>
                    </div>
                    <div>
                        <span class="label">Evolucao percebida</span>
                        <div class="value">{{ $sessao->evolucao_percebida ?: 'Nao informado' }}</div>
                    </div>
                    <div>
                        <span class="label">Proximo passo</span>
                        <div class="value">{{ $sessao->proximo_passo ?: 'Nao informado' }}</div>
                    </div>
                    <div class="grid" style="grid-template-columns:1fr;">
                        <span class="label">Encaminhamentos definidos</span>
                        <div class="value">{{ $sessao->encaminhamentos_definidos ?: 'Nao informado' }}</div>
                    </div>
                </div>
            </div>
        @empty
            <p class="muted">Nenhuma sessao registrada.</p>
        @endforelse
    </div>

    <div class="section">
        <h3>Encerramento e orientacoes finais</h3>
        <div class="grid">
            <div>
                <span class="label">Status atual</span>
                <div class="value">{{ ucfirst(str_replace('_',' ',$atendimento->status)) }}</div>
            </div>
            <div>
                <span class="label">Data de encerramento</span>
                <div class="value">{{ $atendimento->data_encerramento ? $atendimento->data_encerramento->format('d/m/Y') : 'Nao encerrado' }}</div>
            </div>
        </div>
        <div class="row">
            <span class="label">Orientacoes finais</span>
            <div class="value">{{ $atendimento->orientacoes_finais ?: 'Nao informado' }}</div>
        </div>
    </div>
</body>
</html>
