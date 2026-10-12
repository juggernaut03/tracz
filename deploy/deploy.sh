#!/bin/bash
# Pull the latest code and rebuild/restart Tracz. Run on the server as root.
set -euo pipefail
REPO_DIR=/opt/tracz
BRANCH=${1:-main}

cd "$REPO_DIR"
git fetch origin "$BRANCH"
git checkout -q "$BRANCH"
git reset -q --hard "origin/$BRANCH"
echo "Deploying $(git log --oneline -1)"

/usr/local/bin/leantime-backup.sh || echo "warning: pre-deploy backup failed"

docker compose -f deploy/docker-compose.yml build leantime
docker compose -f deploy/docker-compose.yml up -d
docker image prune -f >/dev/null

for i in $(seq 1 30); do
  status=$(docker inspect -f '{{.State.Health.Status}}' leantime 2>/dev/null || echo starting)
  [ "$status" = healthy ] && { echo "leantime is healthy"; exit 0; }
  sleep 5
done
echo "leantime did not become healthy; check: docker logs leantime" >&2
exit 1
