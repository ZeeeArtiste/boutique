Groupe : DERENSY Dany et GRANIER Antoine

# E-boutique

Déploiement d'une e-boutique sur AWS avec des conteneurs Docker :
 - HTTP (Serveur Nginx)
 - Script (Application Symfony)
 - DB (MariaDB)

Les instances sont créées avec Terraform et les conteneurs sont déployés via un playbook Ansible.

## Lancement

- Ajouter sa SECRET_KEY et son ACCESS_key dans un fichier `.env` et dans le fichier `aws_ec2.yml`
- Lancer les commandes suivantes :
  ```shell
    docker image build -t ansible:2.16 .
    docker container run -it --rm -v $PWD:$PWD -w $PWD hashicorp/terraform init
    docker container run -it --rm -v $PWD:$PWD -w $PWD hashicorp/terraform plan
    docker container run -it --rm -v $PWD:$PWD -w $PWD hashicorp/terraform apply
  ```
- Répondre `yes`
- Lancer le playbook :
  ```shell
    docker container run ansible:2.16 ansible-playbook -i aws_ec2.yml playbook.yml
  ```
- Accéder à l'application via l'URL de l'instance HTTP avec le protocol `http` et non `https`.
