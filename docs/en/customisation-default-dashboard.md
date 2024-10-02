---
layout: default
---

# Changing the default dashboard

We can customise the dashboard to display anything that we like.

In this example we will change the default dashboard to display only a few panels.

## Customising the default dashboard

Say we would like to remove some of the default panels, change the order of panels and change some panel widths.

First we create an `app/_config/dashboard.yml` config file.

```yml
---
Name: dashboard-custom
After: '#dashboard'
---
Plastyk\Dashboard\Panels\RecentlyCreatedPagesPanel:
  columns: 6
  sort: 10

Plastyk\Dashboard\Panels\RecentlyEditedPagesPanel:
  enabled: false

Plastyk\Dashboard\Panels\UsefulLinksPanel:
  enabled: true
  columns: 6
  sort: 20
```

We can use the config to remove existing panels, change the order of panels, change the widths of the panels displayed and change the section that the panels belong to.
