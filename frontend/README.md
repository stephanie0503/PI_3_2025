# Orcanet Front-End

This template should help get you started developing with Vue 3 in Vite.

## Configuração recomendada

[VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=Vue.volar) (and disable Vetur).

## Para customizar configurações

Link [Vite Configuration Reference](https://vitejs.dev/config/).

## Configuração do Projeto

- Instalação de dependências do Projeto:

```sh
npm install
```

- Compilar e executar em modo de desenvolvimento (com recarregamento automático):

```sh
npm run dev
```

- Compilar e minificar para produção:

```sh
npm run build
```


- Configuração do arquivo .htaccess:  

```html
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.html [L]
</IfModule>
```