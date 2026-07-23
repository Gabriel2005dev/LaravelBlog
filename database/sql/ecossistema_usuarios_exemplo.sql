-- Ecossistema de conteúdo para o LaravelBlog
-- Pronto para executar no MySQL Workbench.
-- Mantém apenas os 3 usuários já existentes no anexo:
--   1: Gabriel Araújo <exemplo001@gmail.com>
--   2: Beatriz Silva <exemplo002@gmail.com>
--   3: João Pedro <exemplo003@gmail.com>
--
-- O script recria posts, comentários, curtidas e salvos em torno desses usuários,
-- sem criar novos usuários.

START TRANSACTION;


-- Sincroniza os dados básicos dos 3 usuários sem alterar suas senhas.
UPDATE users
SET name = 'Gabriel Araújo', updated_at = NOW()
WHERE id = 1 AND email = 'exemplo001@gmail.com';

UPDATE users
SET name = 'Beatriz Silva', updated_at = NOW()
WHERE id = 2 AND email = 'exemplo002@gmail.com';

UPDATE users
SET name = 'João Pedro', updated_at = NOW()
WHERE id = 3 AND email = 'exemplo003@gmail.com';

INSERT INTO posts (id, user_id, title, slug, body, created_at, updated_at) VALUES
(1, 1, 'Organizando a primeira semana do LaravelBlog', 'organizando-primeira-semana-laravelblog', 'Comecei a semana revisando o feed, separando ideias de melhorias e combinando com a Beatriz e o João uma rotina simples: publicar aprendizados, comentar dúvidas e salvar referências úteis. A proposta é transformar o blog em um diário colaborativo de evolução do projeto.', '2026-07-22 08:10:00', '2026-07-22 08:10:00'),
(2, 2, 'Checklist visual para deixar o feed mais acolhedor', 'checklist-visual-feed-acolhedor', 'Montei um checklist rápido para avaliar espaçamento, contraste, avatares e mensagens vazias. Pequenos ajustes de interface ajudam quem chega no site a entender que existe atividade real acontecendo entre os usuários.', '2026-07-22 09:25:00', '2026-07-22 09:25:00'),
(3, 3, 'Como estou testando comentários e curtidas', 'como-estou-testando-comentarios-curtidas', 'Usei os três perfis do anexo para simular conversas naturais: um usuário publica, outro comenta, e o terceiro salva ou curte. Assim fica mais fácil validar contadores, permissões e a experiência de navegação.', '2026-07-22 10:40:00', '2026-07-22 10:40:00'),
(4, 1, 'Ideias para próximos posts técnicos', 'ideias-proximos-posts-tecnicos', 'Quero escrever sobre autenticação, upload de avatar e boas práticas para componentes Blade. A ideia é que cada post tenha exemplos pequenos, objetivos e conectados ao que já existe no LaravelBlog.', '2026-07-22 14:15:00', '2026-07-22 14:15:00'),
(5, 2, 'Design simples também conta uma história', 'design-simples-tambem-conta-uma-historia', 'Quando o banco está vazio, a aplicação parece inacabada. Com publicações, comentários, curtidas e posts salvos, o visitante entende rapidamente como o produto deve funcionar.', '2026-07-22 16:05:00', '2026-07-22 16:05:00'),
(6, 3, 'Retrospectiva do trio no projeto', 'retrospectiva-trio-projeto', 'Gabriel puxou a organização, Beatriz cuidou da leitura visual e eu foquei nos testes de interação. Esse ciclo pequeno já cria um ecossistema suficiente para demonstrar o site em funcionamento.', '2026-07-23 09:00:00', '2026-07-23 09:00:00');

INSERT INTO comments (id, post_id, user_id, body, created_at, updated_at) VALUES
(1, 1, 2, 'Gostei da ideia do diário colaborativo. Isso ajuda a manter o histórico das decisões visível para todos.', '2026-07-22 08:35:00', '2026-07-22 08:35:00'),
(2, 1, 3, 'Também posso registrar os cenários que eu testar para facilitar a revisão depois.', '2026-07-22 08:48:00', '2026-07-22 08:48:00'),
(3, 2, 1, 'Esse checklist vai ajudar muito na apresentação. Principalmente os estados vazios e os avatares.', '2026-07-22 09:50:00', '2026-07-22 09:50:00'),
(4, 2, 3, 'Vou validar no celular para ver se o espaçamento continua confortável.', '2026-07-22 10:05:00', '2026-07-22 10:05:00'),
(5, 3, 1, 'Boa abordagem. Com dados reais entre nós três fica mais fácil encontrar bugs de relacionamento.', '2026-07-22 11:00:00', '2026-07-22 11:00:00'),
(6, 3, 2, 'Se precisar, eu reviso os textos dos feedbacks para parecerem mais naturais no feed.', '2026-07-22 11:18:00', '2026-07-22 11:18:00'),
(7, 4, 2, 'Post sobre componentes Blade seria ótimo. Podemos incluir exemplos dos cards e do menu do usuário.', '2026-07-22 14:45:00', '2026-07-22 14:45:00'),
(8, 4, 3, 'Autenticação também é um tema importante para explicar o fluxo completo.', '2026-07-22 15:10:00', '2026-07-22 15:10:00'),
(9, 5, 1, 'Concordo. Conteúdo inicial bem pensado muda totalmente a primeira impressão.', '2026-07-22 16:25:00', '2026-07-22 16:25:00'),
(10, 5, 3, 'Esse post resume exatamente por que estamos criando o ecossistema.', '2026-07-22 16:40:00', '2026-07-22 16:40:00'),
(11, 6, 1, 'Fechamos um bom ciclo. Vou salvar esse post para usar como referência da entrega.', '2026-07-23 09:20:00', '2026-07-23 09:20:00'),
(12, 6, 2, 'A retrospectiva ficou clara e mostra o papel de cada um no projeto.', '2026-07-23 09:35:00', '2026-07-23 09:35:00');

INSERT INTO post_likes (post_id, user_id, created_at, updated_at) VALUES
(1, 2, '2026-07-22 08:36:00', '2026-07-22 08:36:00'),
(1, 3, '2026-07-22 08:49:00', '2026-07-22 08:49:00'),
(2, 1, '2026-07-22 09:51:00', '2026-07-22 09:51:00'),
(2, 3, '2026-07-22 10:06:00', '2026-07-22 10:06:00'),
(3, 1, '2026-07-22 11:01:00', '2026-07-22 11:01:00'),
(3, 2, '2026-07-22 11:19:00', '2026-07-22 11:19:00'),
(4, 2, '2026-07-22 14:46:00', '2026-07-22 14:46:00'),
(4, 3, '2026-07-22 15:11:00', '2026-07-22 15:11:00'),
(5, 1, '2026-07-22 16:26:00', '2026-07-22 16:26:00'),
(5, 3, '2026-07-22 16:41:00', '2026-07-22 16:41:00'),
(6, 1, '2026-07-23 09:21:00', '2026-07-23 09:21:00'),
(6, 2, '2026-07-23 09:36:00', '2026-07-23 09:36:00');

INSERT INTO saved_posts (post_id, user_id, created_at, updated_at) VALUES
(1, 2, '2026-07-22 08:37:00', '2026-07-22 08:37:00'),
(1, 3, '2026-07-22 08:50:00', '2026-07-22 08:50:00'),
(2, 1, '2026-07-22 09:52:00', '2026-07-22 09:52:00'),
(3, 2, '2026-07-22 11:20:00', '2026-07-22 11:20:00'),
(4, 3, '2026-07-22 15:12:00', '2026-07-22 15:12:00'),
(5, 1, '2026-07-22 16:27:00', '2026-07-22 16:27:00'),
(6, 1, '2026-07-23 09:22:00', '2026-07-23 09:22:00'),
(6, 2, '2026-07-23 09:37:00', '2026-07-23 09:37:00');
