@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Cadastro de Cliente</h4>

    <form id="formCadastro">
        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Cidade:</label>
            <select name="cidade_id" id="cidadeCadastro" class="form-control" required></select>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
    </form>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const cidadeSelect = document.getElementById('cidadeCadastro');
            const res = await fetch('/api/v1/cidades');
            const cidades = await res.json();
            cidadeSelect.innerHTML = '<option value="">Selecione</option>';
            cidades.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.nome;
                cidadeSelect.appendChild(opt);
            });

            document.getElementById('formCadastro').addEventListener('submit', async e => {
                e.preventDefault();
                const dados = Object.fromEntries(new FormData(e.target));
                const res = await fetch('/api/v1/clientes', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(dados)
                });

                if (res.ok) {
                    alert('Cliente cadastrado com sucesso!');
                    e.target.reset();
                } else {
                    alert('Erro ao cadastrar cliente.');
                }
            });
        });
    </script>
@endpush
