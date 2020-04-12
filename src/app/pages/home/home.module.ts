import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HomeComponent} from './home.component';
import {RouterModule} from '@angular/router';
import {HOME_ROUTES} from './home-routing';
import {TranslateModule} from '@ngx-translate/core';
import {NgStripTagsPipeModule} from 'angular-pipes';
import {PipesModule} from 'src/app/modules/pipes/pipes.module';
import {MatCardModule} from '@angular/material/card';
import {MatButtonModule} from '@angular/material/button';
import {NewsComponent} from '../../components/news/news.component';
import {MiniCardsComponent} from 'src/app/components/mini-cards/mini-cards.component';
import {NewsModule} from 'src/app/components/news/news.module';
import {GenesSectionModule} from 'src/app/modules/genes-section/genes-section.module';

@NgModule({
  declarations: [
    HomeComponent,
    MiniCardsComponent,
    NewsComponent
  ],
  imports: [
    CommonModule,
    RouterModule.forChild(HOME_ROUTES),
    TranslateModule,
    NgStripTagsPipeModule,
    PipesModule,
    MatCardModule,
    MatButtonModule,
    NewsModule,
    GenesSectionModule
  ],
  exports: [
    PipesModule
  ]
})
export class HomeModule {
}
