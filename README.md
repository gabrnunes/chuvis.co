# Chuvisco

O site https://chuvis.co foi criado usando o Wordpress. Este é o repositório que contém o tema do site. Por ser considerado um projeto open source, você pode contribuir com melhorias no tema como preferir.

## Para rodar o projeto

Você precisa ter o `npm`, o `grunt` e o `docker` instalado.

Clone o repositório

```bash
git clone git@github.com:gabrnunes/chuvis.co.git
```

Acesse a pasta do repositório e rode:

```bash
docker-compose up
```

Dentro do projeto acesse a pasta do tema e rode o `grunt`:

```bash
cd wp-content/themes/chuvisco
grunt watch
```
