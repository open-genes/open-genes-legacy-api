import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {TranslateModule} from '@ngx-translate/core';
import {NgStripTagsPipeModule} from 'angular-pipes';
import {PipesModule} from 'src/app/modules/pipes/pipes.module';
import {LoaderPlaceholderComponent} from 'src/app/components/loader-placeholder/loader-placeholder.component';

@NgModule({
  declarations: [
    LoaderPlaceholderComponent
  ],
  imports: [
    CommonModule,
    TranslateModule,
    NgStripTagsPipeModule,
    PipesModule,
  ],
  exports: [
    PipesModule,
    LoaderPlaceholderComponent
  ]
})
export class NewsModule {
}
