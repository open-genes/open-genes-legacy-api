import {
  ApplicationRef,
  ComponentFactoryResolver,
  ComponentRef,
  Directive,
  ElementRef,
  EmbeddedViewRef,
  HostBinding,
  Injector,
  Input,
  NgZone,
  OnDestroy,
  OnInit,
  TemplateRef
} from '@angular/core';
import { AppMenuComponent } from '../components/app-menu/app-menu.component';
import { Observable, Subject, fromEvent} from 'rxjs';
import { filter, takeUntil } from 'rxjs/operators';
import { MENU_POSITION } from '../conf/menu-position.enum';
import {MENU_RELATIVE} from '../conf/menu-relative.enum';
import { DomSanitizer } from '@angular/platform-browser';

@Directive({
  selector: '[Menu]'
})
export class MenuDirective implements OnInit, OnDestroy {

  @Input()
  menuTemplate: TemplateRef<any>;
  @Input()
  menuPosition: MENU_POSITION = MENU_POSITION.bottom;
  @Input()
  fitByTriggerElement = true;
  @Input()
  menuRelative: MENU_RELATIVE.static;

  menuMarginPx = 4;

  @HostBinding('class.menu-trigger-button')
  baseClass = true;

  @HostBinding('class.__active')
  isMenuOpen = false;

  private menuRef: ComponentRef<AppMenuComponent>;
  private unsubscribe$ = new Subject<void>();

  constructor(
    private elRef: ElementRef,
    private componentFactoryResolver: ComponentFactoryResolver,
    private appRef: ApplicationRef,
    private injector: Injector,
    private sanitizer: DomSanitizer,
    private zone: NgZone,
  ) {
  }

  ngOnInit(): void {
    // clicks on trigger button
    fromEvent<Event>(this.elRef.nativeElement, 'click')
      .pipe(takeUntil(this.unsubscribe$))
      .subscribe(() => !this.isMenuOpen ? this.openMenu() : this.closeMenu());

    // clicks not on menu and trigger button
    fromEvent<Event>(document, 'click')
      .pipe(
        takeUntil(this.unsubscribe$),
        filter(event => this.isMenuOpen
          && !this.elRef.nativeElement.contains(event.target)
          && !this.menuRef.instance.elRef.nativeElement.contains(event.target))
      )
      .subscribe(() => this.closeMenu());
  }

  ngOnDestroy(): void {
    this.unsubscribe$.next();
    this.unsubscribe$.complete();
    if (this.menuRef) {
      this.detachMenu();
    }
  }

  private openMenu(): void {
    this.appendMenuToBody();
    this.setMenuStyles();
    this.zone.runOutsideAngular(() => window.addEventListener('resize', this.onWindowResize));
    console.log(window);
    this.zone.runOutsideAngular(() => window.addEventListener('scroll', this.onScroll));
    this.isMenuOpen = true;
    this.menuRef.instance.isMenuVisible = true;
  }

  private closeMenu(): void {
    this.isMenuOpen = false;
    window.removeEventListener('resize', this.onWindowResize);
    window.removeEventListener('scroll', this.onScroll);
    this.detachMenu();
  }

  // TODO: с положением меню есть баг когда родитель имеет свойство position: fixed, добавлено временное решение
  private onWindowResize = () => {
    if (this.isMenuOpen) {
      if (this.menuRelative === 'static') {
        this.setMenuStyles();
      } else {
        this.closeMenu();
      }
    }
  }

  private onScroll = () => {
    if (this.isMenuOpen) {
      if (this.menuRelative === 'static') {
        this.setMenuStyles();
      } else {
        this.closeMenu();
      }
    }
  }

  private appendMenuToBody(): void {
    this.menuRef = this.componentFactoryResolver
      .resolveComponentFactory(AppMenuComponent)
      .create(this.injector);
    this.menuRef.instance.menuTemplate = this.menuTemplate;
    this.appRef.attachView(this.menuRef.hostView);
    const domElem = (this.menuRef.hostView as EmbeddedViewRef<any>).rootNodes[0] as HTMLElement;
    document.body.appendChild(domElem);
  }

  private detachMenu(): void {
    this.appRef.detachView(this.menuRef.hostView);
    this.menuRef.destroy();
    this.menuRef = null;
  }

  private setMenuStyles(): void {
    const rules = [];
    const triggerElLeft = this.elRef.nativeElement.getBoundingClientRect().left;
    const triggerElWidth = this.elRef.nativeElement.offsetWidth;
    rules.push(`left: ${triggerElLeft + triggerElWidth / 2}px;`);
    if (this.fitByTriggerElement) {
      rules.push(`width: ${triggerElWidth}px;`);
    }
    rules.push(this.getMenuPositionRule(this.menuPosition));
    this.menuRef.instance.safeStyles = this.sanitizer.bypassSecurityTrustStyle(rules.join(''));
    // TODO: Добавить проверку положения меню вне вьюпорта
  }

  private getMenuPositionRule(position: MENU_POSITION): string {
    let positionRule;
    const scrollYOffset = window.pageYOffset;
    const { top: triggerElTop, bottom: triggerElBottom } = this.elRef.nativeElement.getBoundingClientRect();
    switch (position) {
      case MENU_POSITION.bottom:
        positionRule = `top: ${triggerElBottom + scrollYOffset + this.menuMarginPx}px;`;
        break;
      case MENU_POSITION.top:
        positionRule =
          `bottom: ${document.body.clientHeight - triggerElTop - scrollYOffset + this.menuMarginPx}px;`;
        break;
    }
    return positionRule;
  }
}
