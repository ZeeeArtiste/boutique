locals {
  envs = { for tuple in regexall("(.*)=(.*)", file(".env")) : tuple[0] => tuple[1] }
}

provider "aws" {
  region     = "eu-north-1"
  access_key = local.envs["ACCESS_KEY"]
  secret_key = local.envs["SECRET_KEY"]
}

resource "aws_instance" "http_server" {
  ami                    = "ami-0705384c0b33c194c"
  instance_type          = "t3.micro"
  key_name               = "myKey"
  vpc_security_group_ids = [
    aws_security_group.web_server_security.id,
    aws_security_group.ssh_security.id
  ]

  tags = {
    Name = "HTTP"
    tags = "ubuntu"
  }
}

resource "aws_instance" "script_server" {
  ami                    = "ami-0705384c0b33c194c"
  instance_type          = "t3.micro"
  key_name               = "myKey"
  vpc_security_group_ids = [
    aws_security_group.script_security.id,
    aws_security_group.ssh_security.id
  ]

  tags = {
    Name = "Script"
    tags = "ubuntu"
  }
}

resource "aws_instance" "db_server" {
  ami                    = "ami-0705384c0b33c194c"
  instance_type          = "t3.micro"
  key_name               = "myKey"
  vpc_security_group_ids = [
    aws_security_group.db_security.id,
    aws_security_group.ssh_security.id
  ]

  tags = {
    Name = "DB"
    tags = "ubuntu"
  }
}

resource "aws_security_group" "web_server_security" {
  name = "web_server_security"

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_security_group" "db_security" {
  name = "db_security"

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 3306
    to_port     = 3306
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_security_group" "script_security" {
  name = "script_security"

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 9000
    to_port     = 9000
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_security_group" "ssh_security" {
  name = "ssh_security"

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
}
