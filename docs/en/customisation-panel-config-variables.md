---
layout: default
---

# Panel config variables

Config variables exist for each dashboard panel. These can be set to control if a panel is enabled, the panel sort order, the panel grid column width and which section the panel belongs to. This can be set through a yml config file to modify existing panels, or in the class of custom panels.

## Modifying variables

### yml

```yml
Plastyk\Dasboard\Panels\RecentlyCreatedPagesPanel:
  columns: 6
  enabled: false
  sort: 10
  section: bottom
```

### class

```php
use Plastyk\Dashboard\Model\DashboardPanel;

class CustomPanel extends DashboardPanel
{
    private static $columns = 6;

    private static $enabled = false;

    private static $sort = 10;

    private static $section = 'bottom';

}
```

## Variables

### Columns

`columns` specifies the grid width of the panel. The default value is `4`, which will take up 1/3 of a row. A maximum value of `12` will make the panel take up 100% of a row. To not set a grid column width set `columns` to `null`.

### Enabled

`enabled` will turn the panel on or off. This allows us to hide or show default panels that we do not or do want. The default value is `true`. Set this to `false` to hide any panel.

### Sort

`sort` controls the sort order of panels. The default value is `50`. Set a lower number to show panels earlier. Set a higher number to show panels later.

### Section

`section` allows us to group panels and control what group a panel belongs to. There are 3 initial dashboard panel sections: `top`, `main` and `bottom`. The default value for `section` is `main`, meaning panels will be added to the main section by default. We can create custom sections and assign panels to these sections. For example we could create a `store` section and assign our panels to this group by setting the panel `section` variable to equal `store`.