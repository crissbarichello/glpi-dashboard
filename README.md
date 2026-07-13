# Dashboard Plugin (GLPI)

Plugin de dashboard para GLPI, atualmente em processo de migração para padrões modernos (GLPI 10/11 + PHP 8).

## Status do Projeto

- Estado: `stable`
- Versão atual: `1.0.5`
- Chave do plugin: `dashboard`
- Pasta obrigatória do plugin: `plugins/dashboard`

## Compatibilidade

- GLPI: `>= 10.0.0` e `< 12.0.0`
- PHP: `>= 8.1` (recomendado 8.2+)

Referências no projeto:
- `setup.php` (requirements e hooks)
- `plugin.xml` (metadados de distribuição)
- `composer.json` (requisito de PHP)

## Principais Mudanças da Migração

- Estrutura base atualizada para padrão mais recente de plugin GLPI.
- Instalação/desinstalação revisada (`hook.php`) com SQL idempotente.
- Adequação de compatibilidade com PHP 8.3 em biblioteca legada de PDF (TCPDF embutido).
- Aplicação de proteção CSRF em formulários POST críticos e formulários legados.
- Ajustes de robustez em fluxos de seleção/redirecionamento (tickets/metrics) e carregamento da `index1.php`.

## Instalação

1. Copie este diretório para a pasta de plugins do GLPI com o nome **`dashboard`**:
   - Exemplo: `glpi/plugins/dashboard`
2. No GLPI, acesse:
   - `Configurar > Plugins`
3. Atualize a lista de plugins, instale e ative o plugin.

## Verificação Rápida Pós-Instalação

1. Acesse o menu do plugin.
2. Teste:
   - Página principal (`front/index.php`, `front/index1.php`).
   - Seleção por entidade/grupo.
   - Salvamento de configuração e mapa.
3. Em caso de tela em branco, valide logs do PHP/GLPI.

## Desenvolvimento e Testes

### Lint PHP

```bash
php -l setup.php
php -l hook.php
find . -path './empty' -prune -o -name '*.php' -print0 | xargs -0 -n1 php -l
```

### Observações

- O código ainda possui partes legadas extensas (principalmente relatórios e gráficos).
- A migração está sendo feita por etapas para reduzir regressão funcional.

## Roadmap Curto

1. Continuidade de hardening (sanitização/fluxos de entrada).
2. Revisão de links/rotas legadas e UX de navegação.
3. Modernização gradual de módulos de relatório e métricas.
4. Preparação para pipeline de build/testes automatizados.

## Licença

Este projeto mantém licença `GPLv2+` (ver `COPYING.txt`).
