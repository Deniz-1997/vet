import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {AgePipe} from './pipes/age.pipe';
import {AggressiveCountPipe} from './pipes/aggressive-count.pipe';
import {DateParsePipe} from './pipes/date-parse.pipe';
import {DefaultValuePipe} from './pipes/default-value.pipe';
import {FileSizePipe} from './pipes/file-size.pipe';
import {FirstLetterPipe} from './pipes/first-letter.pipe';
import {FullTextSearchPipe} from './pipes/full-text-search.pipe';
import {GenderPipe} from './pipes/gender.pipe';
import {MaxNumberPipe} from './pipes/max-number.pipe';
import {PricePipe} from './pipes/price.pipe';
import {SafeHtmlPipe} from './pipes/safe-html.pipe';
import {TitleCaseNamePipe} from './pipes/title-case-name';
import {WeightPipe} from './pipes/weight.pipe';



@NgModule({
  declarations: [
    AgePipe,
    AggressiveCountPipe,
    DateParsePipe,
    DefaultValuePipe,
    FileSizePipe,
    FirstLetterPipe,
    FullTextSearchPipe,
    GenderPipe,
    MaxNumberPipe,
    PricePipe,
    SafeHtmlPipe,
    TitleCaseNamePipe,
    WeightPipe
  ],
  imports: [
    CommonModule
  ],
  exports: [
    AgePipe,
    AggressiveCountPipe,
    DateParsePipe,
    DefaultValuePipe,
    FileSizePipe,
    FirstLetterPipe,
    FullTextSearchPipe,
    GenderPipe,
    MaxNumberPipe,
    PricePipe,
    SafeHtmlPipe,
    TitleCaseNamePipe,
    WeightPipe
  ]
})
export class KorvetPipesModule { }
