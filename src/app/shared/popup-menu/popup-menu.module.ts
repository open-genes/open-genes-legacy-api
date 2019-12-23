import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PopupMenuComponent } from './components/popup-menu/popup-menu.component';
import { PopupMenuItemComponent } from './components/popup-menu-item/popup-menu-item.component';
import { PopupMenuIdDirective } from './directives/popup-menu-id.directive';



@NgModule({
  declarations: [
    PopupMenuComponent,
    PopupMenuItemComponent,
    PopupMenuIdDirective
  ],
  exports: [
    PopupMenuComponent,
    PopupMenuItemComponent,
    PopupMenuIdDirective,
  ],
  imports: [
    CommonModule
  ]
})
export class PopupMenuModule { }
