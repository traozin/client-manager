SELECT r.*
FROM representantes r
JOIN cliente_representante cr ON cr.representante_id = r.id
WHERE cr.cliente_id = :idCliente;