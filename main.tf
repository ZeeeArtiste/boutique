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
  vpc_security_group_ids = [aws_security_group.allow_ssh.id]

  tags = {
    Name = "HTTP"
    tags = "ubuntu"
  }
}

resource "aws_instance" "script_server" {
  ami                    = "ami-0705384c0b33c194c"
  instance_type          = "t3.micro"
  key_name               = "myKey"
  vpc_security_group_ids = [aws_security_group.allow_ssh.id]

  tags = {
    Name = "Script"
    tags = "ubuntu"
  }
}

resource "aws_instance" "db_server" {
  ami                    = "ami-0705384c0b33c194c"
  instance_type          = "t3.micro"
  key_name               = "myKey"
  vpc_security_group_ids = [aws_security_group.allow_ssh.id]

  tags = {
    Name = "DB"
    tags = "ubuntu"
  }
}

resource "aws_security_group" "allow_ssh" {
  name = "allow_ssh"

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

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
}
