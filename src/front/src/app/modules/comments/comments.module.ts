import { CommonModule as NgCommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';

import { CommentsScrollDirective } from './scroll';
import { CommonModule } from '../../common/common.module';
import { VideoModule } from '../media/components/video/video.module';
import { TranslateModule } from '../translate/translate.module';
import { ModalsModule } from '../modals/modals.module';
import { CommentsListComponent } from './list/list.component';
import { CommentComponent } from './card/comment.component';
import { CommentComponentV2 } from './comment/comment.component';
import { CommentPosterComponent } from './poster/poster.component';
import { CommentsTreeComponent } from './tree/tree.component';
import { CommentsThreadComponent } from './thread/thread.component';
import { CommentsService } from './comments.service';

@NgModule({
  imports: [
    NgCommonModule,
    FormsModule,
    RouterModule,
    CommonModule,
    VideoModule,
    TranslateModule,
    ModalsModule,
  ],
  declarations: [
    CommentsScrollDirective,
    CommentComponent,
    CommentsListComponent,
    CommentComponentV2,
    CommentPosterComponent,
    CommentsTreeComponent,
    CommentsThreadComponent,
  ],
  exports: [
    CommentsScrollDirective,
    CommentComponent,
    CommentsListComponent,
    CommentComponentV2,
    CommentPosterComponent,
    CommentsTreeComponent,
    CommentsThreadComponent,
  ],
  providers: [
    CommentsService,
  ],
})
export class CommentsModule {
}
