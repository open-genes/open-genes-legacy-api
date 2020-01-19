import { ChangeDetectionStrategy, Component, EventEmitter, HostBinding, Input, Output } from '@angular/core';

@Component({
  selector: 'app-menu-item',
  templateUrl: './app-menu-item.component.html',
  styleUrls: [ './app-menu-item.component.scss' ],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class AppMenuItemComponent {
  @HostBinding('attr.role')
  role = 'listitem';

  @Input()
  text: string;
  @Input()
  target: string;
  @Input()
  isDisabled = false;

  @Output()
  onSelect = new EventEmitter<Event>();

  selectItem(event: Event) {
    this.onSelect.emit(event);
  }
}
