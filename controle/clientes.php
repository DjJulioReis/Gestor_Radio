<?php
require_once 'init.php';
require_once 'src/Cliente.php';

// Define o título da página
$page_title = "Gestão de Clientes";

// Inclui o cabeçalho da página
require_once 'templates/header.php';

// Instancia a classe de cliente e busca todos os clientes
$cliente = new Cliente($conn);
$clientes = $cliente->getAll();

?>

<h1><?php echo htmlspecialchars($page_title); ?></h1>
<a href="dashboard.php">Voltar para o Início</a>

<?php if (isset($_SESSION['user_level']) && $_SESSION['user_level'] === 'admin'): ?>
    <a href="cliente_add.php" class="add-link">Adicionar Novo Cliente</a>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Empresa</th>
            <th>CNPJ/CPF</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Data Cadastro</th>
            <th>Plano</th>
            <th>Status</th>
            <?php if (isset($_SESSION['user_level']) && $_SESSION['user_level'] === 'admin'): ?>
                <th>Ações</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($clientes)): ?>
            <?php foreach ($clientes as $cli): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cli['empresa']); ?></td>
                    <td><?php echo htmlspecialchars($cli['cnpj_cpf']); ?></td>
                    <td><?php echo htmlspecialchars($cli['email']); ?></td>
                    <td><?php echo htmlspecialchars($cli['telefone']); ?></td>
                    <td><?php echo $cli['data_cadastro'] ? date("d/m/Y", strtotime($cli['data_cadastro'])) : "-"; ?></td>
                    <td><?php echo $cli['plano'] ? htmlspecialchars($cli['plano']) : '—'; ?></td>
                    <td>
                        <?php if ($cli['ativo']): ?>
                            <span style="color: green; font-weight: bold;">Ativo</span>
                        <?php else: ?>
                            <span style="color: red; font-weight: bold;">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <?php if (isset($_SESSION['user_level']) && $_SESSION['user_level'] === 'admin'): ?>
                        <td class="actions">
                            <a href="cliente_edit.php?id=<?php echo $cli['id']; ?>">Editar</a>
                            <!-- O link de exclusão pode ser refatorado da mesma forma -->
                            <a href="src/cliente_delete_handler.php?id=<?php echo $cli['id']; ?>"
                               onclick="return confirm('Tem certeza que deseja excluir este cliente?');">
                                Excluir
                            </a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?php echo (isset($_SESSION['user_level']) && $_SESSION['user_level'] === 'admin') ? '8' : '7'; ?>">
                    Nenhum cliente cadastrado.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
// A conexão com o banco de dados é fechada no final do script, se necessário, ou pode ser omitida
// se a conexão for persistente.
require_once 'templates/footer.php';
?>
