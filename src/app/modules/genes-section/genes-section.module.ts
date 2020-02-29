import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import { TranslateModule } from '@ngx-translate/core';
import {PipesModule} from '../pipes/pipes.module';
import {RouterModule} from '@angular/router';
import {MatButtonModule} from '@angular/material/button';
import { SearchComponent } from './search/search.component';
import { SearchModule } from './search/search.module';
import {GenesSectionComponent} from './genes-section.component';
import {GenesListComponent} from './genes-list/genes-list.component';
import {MatMenuModule} from '@angular/material/menu';
import {MatIconModule} from '@angular/material/icon';
import {ReactiveFormsModule} from '@angular/forms';
import {DirectivesModule} from '../../directives/directives.module';

// import { GenesListService } from './genes-list.service';


@NgModule({
  declarations: [
    GenesSectionComponent,
    GenesListComponent,
    SearchComponent
  ],
  imports: [
    CommonModule,
    TranslateModule,
    PipesModule,
    MatButtonModule,
    RouterModule,
    SearchModule,
    MatMenuModule,
    MatIconModule,
    ReactiveFormsModule,
    DirectivesModule
  ],
  exports: [
    PipesModule,
    GenesSectionComponent
  ]
})
export class GenesSectionModule {
}

