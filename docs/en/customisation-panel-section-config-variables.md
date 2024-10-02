---
layout: default
---

# Panel section config variables

Config variables exist for each dashboard panel section. These can be set to control if a panel section is enabled, the panel section sort order, the panel grid column width and which section the panel belongs to. This can be set through a yml config file to modify existing panels, or in the class of custom panels.

## Modifying variables

### yml

```yml
Plastyk\Dashboard\PanelSections\TopSection:
  enabled: false
  sort: 10
```

### class

```php
use Plastyk\Dashboard\Model\DashboardPanelSection;

class CustomPanelSection extends DashboardPanelSection
{
    private static $enabled = true;

    private static $sort = 10;

    private static $section = 'custom';

}
```

## Variables

### Enabled

`enabled` will turn the panel section on or off. This allows us to hide or show default panel sections that we do not or do want. The default value is `true`. Set this to `false` to hide any panel section.

### Sort

`sort` controls the sort order of panel sections. The default value is `50`. Set a lower number to show panel sections higher. Set a higher number to show panel sections lower.

### Section

`section` defines the section name. It allows us to group panels and control what group a panel belongs to. There are 3 initial dashboard panel sections: `top`, `main` and `bottom`. We can create custom sections and assign panels to these sections. For example we could create a `store` section and assign our panels to this group by setting the panel `section` variable to equal `store`.