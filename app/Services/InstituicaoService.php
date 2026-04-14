<?php

namespace App\Services;

use App\Models\Instituicao;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class InstituicaoService
{
    /**
     * Retorna a instituição única ou cria uma vazia em memória.
     */
    public function obterInstituicao(): Instituicao
    {
        return Instituicao::first() ?? new Instituicao;
    }

    /**
     * Atualiza os dados da instituição e lida com uploads.
     */
    public function atualizarInstituicao(array $dados): Instituicao
    {
        $instituicao = $this->obterInstituicao();

        // Faz o tratamento e upload das imagens
        $this->processarUpload($dados, $instituicao, 'brasao', 'brasao_path');
        $this->processarUpload($dados, $instituicao, 'logo_prefeitura', 'logo_prefeitura_path');
        $this->processarUpload($dados, $instituicao, 'logo_secretaria', 'logo_secretaria_path');

        if ($instituicao->exists) {
            $instituicao->update($dados);
        } else {
            // Se for novo, preenche e salva
            $instituicao->fill($dados);
            $instituicao->save();
        }

        return $instituicao;
    }

    /**
     * Processa o upload de arquivo e deleta o antigo se existir
     */
    private function processarUpload(array &$dados, Instituicao $instituicao, string $campoArquivo, string $campoPathBase): void
    {
        if (isset($dados[$campoArquivo]) && $dados[$campoArquivo] instanceof UploadedFile) {
            // Deletar o antigo
            if ($instituicao->$campoPathBase && Storage::disk('public')->exists($instituicao->$campoPathBase)) {
                Storage::disk('public')->delete($instituicao->$campoPathBase);
            }

            // Subir o novo na subpasta 'institucional'
            $caminho = $dados[$campoArquivo]->store('institucional', 'public');
            $dados[$campoPathBase] = $caminho;
        }

        // Remove do BD field insert as chaves puras dos arquivos (para não estourar column not found)
        unset($dados[$campoArquivo]);
    }
}
