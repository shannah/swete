#!/bin/bash
# Deploy SWeTE to Google Cloud Run
#
# Prerequisites:
#   - gcloud CLI installed and authenticated
#   - A Cloud SQL MySQL instance created
#   - A GCS bucket created for data storage
#
# Environment variables (required):
#   PROJECT_ID          - GCP project ID
#   REGION              - GCP region (e.g., us-central1)
#   SERVICE_NAME        - Cloud Run service name
#   CLOUD_SQL_INSTANCE  - Cloud SQL connection name (project:region:instance)
#   DB_NAME             - Database name
#   DB_USER             - Database user
#   DB_PASSWORD          - Database password (or use Secret Manager)
#   GCS_BUCKET          - GCS bucket name for data storage
#
# Optional:
#   IMAGE_TAG           - Docker image tag (default: latest)
#   CPU                 - CPU limit (default: 1)
#   MEMORY              - Memory limit (default: 512Mi)
#   MAX_INSTANCES       - Max instances (default: 3)
#   MIN_INSTANCES       - Min instances (default: 0)

set -e

PROJECT_ID="${PROJECT_ID:?PROJECT_ID is required}"
REGION="${REGION:-us-central1}"
SERVICE_NAME="${SERVICE_NAME:?SERVICE_NAME is required}"
CLOUD_SQL_INSTANCE="${CLOUD_SQL_INSTANCE:?CLOUD_SQL_INSTANCE is required}"
DB_NAME="${DB_NAME:?DB_NAME is required}"
DB_USER="${DB_USER:?DB_USER is required}"
DB_PASSWORD="${DB_PASSWORD:?DB_PASSWORD is required}"
GCS_BUCKET="${GCS_BUCKET:?GCS_BUCKET is required}"
IMAGE_TAG="${IMAGE_TAG:-latest}"
CPU="${CPU:-1}"
MEMORY="${MEMORY:-512Mi}"
MAX_INSTANCES="${MAX_INSTANCES:-3}"
MIN_INSTANCES="${MIN_INSTANCES:-0}"

IMAGE="gcr.io/${PROJECT_ID}/swete:${IMAGE_TAG}"

echo "==> Building Docker image..."
gcloud builds submit --tag "${IMAGE}" --project "${PROJECT_ID}"

echo "==> Deploying to Cloud Run..."
gcloud run deploy "${SERVICE_NAME}" \
    --image "${IMAGE}" \
    --platform managed \
    --region "${REGION}" \
    --project "${PROJECT_ID}" \
    --add-cloudsql-instances "${CLOUD_SQL_INSTANCE}" \
    --set-env-vars "CLOUD_SQL_CONNECTION_NAME=${CLOUD_SQL_INSTANCE},DB_NAME=${DB_NAME},DB_USER=${DB_USER},DB_PASSWORD=${DB_PASSWORD},SWETE_DATA_DIR=/mnt/gcs" \
    --cpu "${CPU}" \
    --memory "${MEMORY}" \
    --max-instances "${MAX_INSTANCES}" \
    --min-instances "${MIN_INSTANCES}" \
    --port 8080 \
    --execution-environment gen2 \
    --add-volume name=gcs-data,type=cloud-storage,bucket="${GCS_BUCKET}" \
    --add-volume-mount volume=gcs-data,mount-path=/mnt/gcs \
    --allow-unauthenticated

echo "==> Deployment complete."
gcloud run services describe "${SERVICE_NAME}" --region "${REGION}" --project "${PROJECT_ID}" --format='value(status.url)'
