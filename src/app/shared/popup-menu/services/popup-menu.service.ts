import { Injectable } from '@angular/core';

import { PopupMenuComponent } from '../components/popup-menu/popup-menu.component';

@Injectable({
  providedIn: 'root'
})
export class PopupMenuService {
  private readonly menus: PopupMenuComponent[];

  constructor() {
    this.menus = [];
  }

  /**
   * Регистрация компонента
   * @param menu - Регистрируемый компонент
   */
  register(menu: PopupMenuComponent) {
    this.menus.push(menu);
    console.log('menu registered', this.menus);
  }

  /**
   * Поиск компонента по идентификатору
   * @param id - Идентификатор
   */
  getById(id: string): PopupMenuComponent | null {
    const findMenuById = (item: PopupMenuComponent) => item.id === id;
    const menu = this.menus.find(findMenuById);
    return menu ? menu : null;
  }
}
