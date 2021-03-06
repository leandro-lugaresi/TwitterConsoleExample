// Adds the systems that shape your system
systems({
  "twitter-application": {
    // Dependent systems
    depends: ["mongodb", "rabbitmq"], // postgres, mysql, mongodb ...
    // More images:  http://images.azk.io
    image: {dockerfile: './docker/php-fpm'},
    // Steps to execute before running instances
    provision: [
      "composer install",
    ],
    workdir: "/azk/#{manifest.dir}",
    shell: "/bin/bash",
    wait: {"retry": 20, "timeout": 1000},
    mounts: {
      '/azk/#{manifest.dir}': sync("."),
      '/azk/#{manifest.dir}/vendor': persistent('vendor-#{manifest.dir}'),
    },
    scalable: {"default": 1},
    http: {
      // my-app.dev.azk.io
      domains: [ "#{system.name}.#{azk.default_domain}" ]
    },
    ports: {
      // exports global variables
      http: "80/tcp",
    },
    envs: {
      // set instances variables
      APP_DIR: "/azk/#{manifest.dir}/web",
    },
  },
  "twitter-consumer": {
    // Dependent systems
    depends: ["mongodb", "rabbitmq"],
    image: {dockerfile: './docker/php-cli'},
    provision: [
      "composer install",
    ],
    workdir: "/azk/#{manifest.dir}",
    shell: "/bin/bash",
    mounts: {
      '/azk/#{manifest.dir}': sync("."),
    },
    scalable: {"default": 1},
    envs: {
      // set instances variables
      APP_DIR: "/azk/#{manifest.dir}",
    },
  },
  mongodb: {
    image : { docker: "azukiapp/mongodb" },
    scalable: false,
    wait: {"retry": 20, "timeout": 1000},
    // Mounts folders to assigned paths
    mounts: {
      // equivalent persistent_folders
      '/data/db': persistent('mongodb-#{manifest.dir}'),
    },
    ports: {
      http: "28017/tcp",
    },
    http: {
      // mongodb.azk.dev
      domains: [ "#{manifest.dir}-#{system.name}.#{azk.default_domain}" ],
    },
    export_envs: {
      MONGODB_URI: "mongodb://#{net.host}:#{net.port[27017]}",
      MONGO_PORT: "#{net.port[27017]}",
      MONGO_HOST: "#{net.host}",
    },
  },
  rabbitmq: {
    image: {"docker": "rabbitmq:3.5.3-management"},
    provision: [],
    shell: "/bin/bash",
    wait: {"retry": 25, "timeout": 1000},
    mounts: {
      '/var/lib/rabbitmq': persistent("#{system.name}-#{manifest.dir}"),
    },
    ports: {
      http: "15672",
      amqp: "5672"
    },
    http: {
      // mongodb.azk.dev
      domains: [ "#{manifest.dir}-#{system.name}.#{azk.default_domain}" ],
    },
    envs: {
      // set instances variables
      RABBITMQ_ERLANG_COOKIE: "SecretCookieForDevelopmentPurpose",
      RABBITMQ_DEFAULT_USER: "leandro",
      RABBITMQ_DEFAULT_PASS: "123mudar",
    },
    export_envs: {
        RABBITMQ_HOST: "#{net.host}",
        RABBITMQ_PORT: "#{net.port[5672]}",
        RABBITMQ_USER: "#{envs.RABBITMQ_DEFAULT_USER}",
        RABBITMQ_PASS: "#{envs.RABBITMQ_DEFAULT_PASS}",
        RABBITMQ_VHOST: "/"
    },
  },
});
