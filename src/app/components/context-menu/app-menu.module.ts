import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AppMenuComponent } from './components/app-menu/app-menu.component';
import { AppMenuItemComponent } from './components/app-menu-item/app-menu-item.component';
import { MenuDirective } from './directives/menu.directive';

const EXPORTS = [
  AppMenuComponent,
  AppMenuItemComponent,
  MenuDirective,
];

@NgModule({
  imports: [ CommonModule ],
  declarations: EXPORTS,
  exports: EXPORTS,
  entryComponents: [ AppMenuComponent ]
})
export class MenuModule {
}
