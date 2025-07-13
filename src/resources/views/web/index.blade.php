@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Consulta de Clientes</h4>

    <!-- Filtros -->
    <form id="formConsulta">
        <div class="row">
            <div class="col-md-4 mb-2">
                <input type="text" name="nome" class="form-control" placeholder="Nome do cliente">
            </div>
            <div class="col-md-4 mb-2">
                <select name="cidade_id" id="cidadeFiltro" class="form-control"></select>
            </div>
            <div class="col-md-4 mb-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
                <button type="reset" class="btn btn-secondary" id="btnLimpar">Limpar</button>
            </div>
        </div>
    </form>

    <!-- Tabela -->
    <div class="table-responsive mt-4">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Ações</th>
                    <th>Nome</th>
                    <th>Cidade</th>
                    <th>Representantes</th>
                </tr>
            </thead>
            <tbody id="tabelaClientes"></tbody>
        </table>
        <nav>
            <ul class="pagination" id="paginacao"></ul>
        </nav>
    </div>
</div>

<!-- Modal de Edição -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
        <form id="formEdicao">
            <div class="modal-header">
                <h5 class="modal-title">Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id">
                <div class="mb-3">
                    <label>Nome:</label>
                    <input type="text" name="nome" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Cidade:</label>
                    <select name="cidade_id" class="form-control" id="cidadeEdit"></select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/clientes/index.js') }}"></script>
@endpush
