pipeline {
    agent any

    environment {
        AWS_REGION = 'ap-south-1'                // Change to your AWS region
        AWS_ACCOUNT_ID = '123456789012'          // Replace with your AWS account ID
        ECR_REPO = 'couponlist'                  // Your ECR repository name
        CLUSTER_NAME = 'couponlist-cluster'      // Your ECS cluster name
        SERVICE_NAME = 'couponlist-service'      // Your ECS service name
        IMAGE_TAG = "${env.BUILD_NUMBER}"        // Use Jenkins build number as image tag
    }

    stages {
        stage('Checkout') {
            steps {
                git url: 'https://github.com/alakhdeveloper/couponlist.git', branch: 'main'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh '''
                docker build -t $ECR_REPO:$IMAGE_TAG .
                '''
            }
        }

        stage('Login to ECR') {
            steps {
                sh '''
                aws ecr get-login-password --region $AWS_REGION \
                  | docker login --username AWS --password-stdin $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com
                '''
            }
        }

        stage('Push to ECR') {
            steps {
                sh '''
                docker tag $ECR_REPO:$IMAGE_TAG $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/$ECR_REPO:$IMAGE_TAG
                docker push $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/$ECR_REPO:$IMAGE_TAG
                '''
            }
        }

        stage('Deploy to ECS') {
            steps {
                sh '''
                aws ecs update-service \
                  --cluster $CLUSTER_NAME \
                  --service $SERVICE_NAME \
                  --force-new-deployment \
                  --region $AWS_REGION
                '''
            }
        }
    }

    post {
        success {
            echo "Deployment successful! ECS service $SERVICE_NAME updated with image $IMAGE_TAG"
        }
        failure {
            echo "Deployment failed. Check logs for details."
        }
    }
}
