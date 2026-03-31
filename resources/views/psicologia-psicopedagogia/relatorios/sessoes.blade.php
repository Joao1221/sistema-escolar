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
        .toolbar { display: flex; justify-content: flex-end; margin-bottom: 18px; }
        .print-button { display: inline-flex; align-items: center; border: 1px solid #0f172a; background: #0f172a; color: #fff; border-radius: 14px; padding: 10px 18px; font-size: 14px; font-weight: 700; cursor: pointer; }
        .print-button:hover { background: #020617; }
        .pill { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .pill.blue { background: #e0f2fe; color: #0369a1; }
        .pill.green { background: #dcfce7; color: #166534; }
        
        @page { size: A4; margin: 15mm; }
        
        @media print {
            body { background: #fff; padding: 0; font-size: 10pt; line-height: 1.3; color: #1e293b; }
            .toolbar { display: none !important; }
            
            .header { display: flex; flex-direction: column; border: 2px solid #334155; border-radius: 0; padding: 12px; margin-bottom: 12px; }
            .header .header-top { display: flex; align-items: center; gap: 12px; }
            .header .header-top .brasao { width: 45px; height: 45px; border-radius: 0; }
            .header .header-top .header-info { flex: 1; text-align: left; }
            .header .header-top .header-info .prefeitura { font-size: 14pt; font-weight: 700; color: #0f172a; white-space: nowrap; }
            .header .header-top .header-info .secretaria { font-size: 12pt; color: #334155; margin-top: 2px; }
            .header .header-top .header-info .titulo { font-size: 11pt; font-weight: 600; color: #475569; margin-top: 8px; }
            .header .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-top: 10px; padding-top: 10px; border-top: 1px solid #cbd5e1; }
            .header .grid .label { font-size: 7pt; }
            .header .grid .value { font-size: 9pt; }
            .header .header-titulo { text-align: center; margin-top: 10px; padding-top: 10px; border-top: 1px solid #cbd5e1; }
            .header .header-titulo .value { font-size: 11pt; font-weight: 700; }
            
            h1 { font-size: 16pt; font-weight: 700; color: #0f172a; margin: 0; }
            h2 { font-size: 12pt; font-weight: 700; color: #334155; border-bottom: 1px solid #cbd5e1; padding-bottom: 5px; margin-bottom: 10px; }
            h3 { font-size: 11pt; font-weight: 600; color: #475569; }
            
            .section { 
                box-shadow: none; 
                border: 1px solid #cbd5e1; 
                border-radius: 0; 
                margin-bottom: 15px; 
                padding: 12px; 
                page-break-inside: avoid;
            }
            
            .label { font-size: 7pt; letter-spacing: .05em; color: #64748b; }
            .value { font-size: 9pt; font-weight: 500; }
            
            .session { 
                border: 1px solid #e2e8f0; 
                border-radius: 0; 
                padding: 10px; 
                margin-bottom: 10px;
                page-break-inside: avoid;
                background: #f8fafc;
            }
            
            .session .grid { 
                display: grid; 
                grid-template-columns: repeat(4, 1fr); 
                gap: 8px; 
                margin-bottom: 8px;
            }
            
            .session .row { 
                margin-top: 4px; 
                display: block;
            }
            
            .row .label { 
                display: inline; 
                font-size: 7pt; 
            }
            
            .row .value { 
                display: inline; 
                font-size: 9pt;
            }
            
            .muted { font-size: 8pt; color: #94a3b8; }
            .pill { font-size: 8pt; padding: 2px 6px; }
            
            .brasao { width: 50px; height: 50px; }
            
            .header { display: flex; flex-direction: column; border: 2px solid #334155; border-radius: 0; padding: 12px; margin-bottom: 12px; }
            .header .header-top { display: flex; align-items: center; gap: 12px; }
            .header .header-top .brasao { width: 45px; height: 45px; border-radius: 0; }
            .header .header-top .header-info { flex: 1; text-align: left; }
            .header .header-top .header-info .prefeitura { font-size: 14pt; font-weight: 700; color: #0f172a; white-space: nowrap; }
            .header .header-top .header-info .secretaria { font-size: 12pt; color: #334155; margin-top: 2px; }
            .header .header-top .header-info .titulo { font-size: 11pt; font-weight: 600; color: #475569; margin-top: 8px; }
            .header .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-top: 10px; padding-top: 10px; border-top: 1px solid #cbd5e1; }
            .header .grid .label { font-size: 7pt; }
            .header .grid .value { font-size: 9pt; }
            .header .header-titulo { text-align: center; margin-top: 10px; padding-top: 10px; border-top: 1px solid #cbd5e1; }
            .header .header-titulo .value { font-size: 11pt; font-weight: 700; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" class="print-button" onclick="window.print()">Imprimir</button>
    </div>

    <div class="header">
        <div class="header-top" style="display:flex; gap:14px; align-items:center;">
            @php
                $brasao = $instituicao?->brasao_url;
            @endphp
            <div class="brasao" style="width:70px;height:70px; border-radius:14px; overflow:hidden; background:#f1f5f9; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                @if($brasao)
                    <img src="{{ $brasao }}" alt="Brasao" style="max-width:100%; max-height:100%; object-fit:contain;">
                @else
                    <span class="muted" style="font-size:11px; text-align:center;">Brasao</span>
                @endif
            </div>
            <div class="header-info" style="flex:1; text-align:left;">
                <p class="prefeitura" style="margin:0; font-size:18px; font-weight:700;">{{ $instituicao->nome_prefeitura ?? 'PREFEITURA MUNICIPAL' }}</p>
                <p class="secretaria" style="margin-top:4px; font-size:16px;">{{ $instituicao->nome_secretaria ?? 'Secretaria de Educacao' }}</p>
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
        <div class="header-titulo" style="text-align:center; margin-top:15px;">
            <p class="value" style="font-size:16px; font-weight:700;">Relatorio de atendimento</p>
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

    @if($atendimento->devolutivas->count() > 0)
    <div class="section">
        <h2>Devolutivas</h2>
        @foreach($atendimento->devolutivas as $devolutiva)
            <div class="session">
                <div class="grid">
                    <div>
                        <span class="label">Destinatario</span>
                        <div class="value">{{ ucfirst($devolutiva->destinatario) }}</div>
                    </div>
                    <div>
                        <span class="label">Data</span>
                        <div class="value">{{ $devolutiva->data_devolutiva->format('d/m/Y') }}</div>
                    </div>
                </div>
                <div class="row">
                    <span class="label">Resumo da devolutiva</span>
                    <div class="value">{{ $devolutiva->resumo_devolutiva ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Orientacoes</span>
                    <div class="value">{{ $devolutiva->orientacoes ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Encaminhamentos combinados</span>
                    <div class="value">{{ $devolutiva->encaminhamentos_combinados ?: 'Nao informado' }}</div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    @if($atendimento->reavaliacoes->count() > 0)
    <div class="section">
        <h2>Reavaliacoes</h2>
        @foreach($atendimento->reavaliacoes as $reavaliacao)
            <div class="session">
                <div class="grid">
                    <div>
                        <span class="label">Data</span>
                        <div class="value">{{ $reavaliacao->data_reavaliacao->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <span class="label">Decisao</span>
                        <div class="value">{{ ucfirst(str_replace('_', ' ', $reavaliacao->decisao)) }}</div>
                    </div>
                </div>
                <div class="row">
                    <span class="label">Progresso observado</span>
                    <div class="value">{{ $reavaliacao->progresso_observado ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Dificuldades persistentes</span>
                    <div class="value">{{ $reavaliacao->dificuldades_persistentes ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Ajuste do plano</span>
                    <div class="value">{{ $reavaliacao->ajuste_plano ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Justificativa</span>
                    <div class="value">{{ $reavaliacao->justificativa ?: 'Nao informado' }}</div>
                </div>
                @if($reavaliacao->proxima_reavaliacao)
                <div class="row">
                    <span class="label">Proxima reavaliacao</span>
                    <div class="value">{{ $reavaliacao->proxima_reavaliacao->format('d/m/Y') }}</div>
                </div>
                @endif
            </div>
        @endforeach
    </div>
    @endif

    @if($atendimento->encaminhamentos->count() > 0)
    <div class="section">
        <h2>Encaminhamentos</h2>
        @foreach($atendimento->encaminhamentos as $encaminhamento)
            <div class="session">
                <div class="grid">
                    <div>
                        <span class="label">Tipo</span>
                        <div class="value">{{ ucfirst($encaminhamento->tipo) }}</div>
                    </div>
                    <div>
                        <span class="label">Destino</span>
                        <div class="value">{{ $encaminhamento->destino }}</div>
                    </div>
                    <div>
                        <span class="label">Data</span>
                        <div class="value">{{ $encaminhamento->data_encaminhamento->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <span class="label">Status</span>
                        <div class="value">{{ ucfirst(str_replace('_', ' ', $encaminhamento->status)) }}</div>
                    </div>
                </div>
                <div class="row">
                    <span class="label">Motivo</span>
                    <div class="value">{{ $encaminhamento->motivo ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Profissional de destino</span>
                    <div class="value">{{ $encaminhamento->profissional_destino ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Instituicao de destino</span>
                    <div class="value">{{ $encaminhamento->instituicao_destino ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Orientacoes sigilosas</span>
                    <div class="value">{{ $encaminhamento->orientacoes_sigilosas ?: 'Nao informado' }}</div>
                </div>
                @if($encaminhamento->retorno_previsto_em)
                <div class="row">
                    <span class="label">Retorno previsto em</span>
                    <div class="value">{{ $encaminhamento->retorno_previsto_em->format('d/m/Y') }}</div>
                </div>
                @endif
            </div>
        @endforeach
    </div>
    @endif

    @if($atendimento->planosIntervencao->count() > 0)
    <div class="section">
        <h2>Planos de Intervencao</h2>
        @foreach($atendimento->planosIntervencao as $plano)
            <div class="session">
                <div class="grid">
                    <div>
                        <span class="label">Objetivo geral</span>
                        <div class="value">{{ $plano->objetivo_geral }}</div>
                    </div>
                    <div>
                        <span class="label">Status</span>
                        <div class="value">{{ ucfirst(str_replace('_', ' ', $plano->status)) }}</div>
                    </div>
                    <div>
                        <span class="label">Data inicio</span>
                        <div class="value">{{ $plano->data_inicio->format('d/m/Y') }}</div>
                    </div>
                    @if($plano->data_fim)
                    <div>
                        <span class="label">Data fim</span>
                        <div class="value">{{ $plano->data_fim->format('d/m/Y') }}</div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <span class="label">Objetivos especificos</span>
                    <div class="value">{{ $plano->objetivos_especificos ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Estrategias</span>
                    <div class="value">{{ $plano->estrategias ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Responsaveis pela execucao</span>
                    <div class="value">{{ $plano->responsaveis_execucao ?: 'Nao informado' }}</div>
                </div>
                <div class="row">
                    <span class="label">Observacoes sigilosas</span>
                    <div class="value">{{ $plano->observacoes_sigilosas ?: 'Nao informado' }}</div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</body>
</html>
