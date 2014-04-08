# Password and local git repository location stored outside VCS
load 'app/config/deploy_config'

# Credentials information
set  :application, "reklama"
set  :domain,      "projektai.nfqakademija.lt"
set  :user,        "reklama"
set  :deploy_to,   "/home/reklama/cap/"
set :port, 22129

# Permissions
set :writable_dirs,       ["app/cache", "app/logs"]
set :webserver_user,      "www-data"
set :permission_method,   :acl
set :use_set_permissions, true

# Repository
set   :scm,              :git

# Allow branch select from command line. Eg.: cap -S branch=f-my-feature deploy
set :branch, fetch(:branch, "master")

# Deploy by resolving composer dependencies locally and then uploading
set   :deploy_via,       :capifony_copy_local
set   :use_composer,     true
set   :use_composer_tmp, true

role  :web,           domain
role  :app,           domain, :primary => true

# xo server user has no sudo rights
set   :use_sudo,      false
set   :keep_releases, 3

# Do not ask whether to migrate database
set :interactive_mode, false

# Uncomment following to enable debug info printing
logger.level = Logger::MAX_LEVEL

# Shared files are kept on server and shared between releases by sym-linking
set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", app_path + "/sessions", web_path + "/uploads"]

# Chore tasks
before "symfony:cache:warmup", "symfony:doctrine:migrations:migrate", "symfony:assetic:dump"

# Uncomment following to disable cache warm-up.
set :cache_warmup, false

# Clear *_dev controllers
set :clear_controllers, false

# Hacky fix to allow www-data to write into cache, because we deploy and therefore create files with a different user.
after "deploy:finalize_update" do
  run "chmod -Rf 777 #{latest_release}/#{cache_path}"
end