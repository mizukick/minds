import { Component } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { RouterLink, RouteParams } from "angular2/router";

import { GroupsService } from '../../groups-service';

import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';
import { Material } from '../../../../directives/material';
import { InfiniteScroll } from '../../../../directives/infinite-scroll';
import { UserCard } from '../../../../controllers/cards/cards';

import { GroupsProfileMembersInvite } from './invite/invite';
import { GroupsCardUserActionsButton } from '../card-user-actions-button';


@Component({
  selector: 'minds-groups-profile-members',

  inputs: ['_group : group'],
  templateUrl: 'src/plugins/Groups/profile/members/members.html',
  directives: [ CORE_DIRECTIVES, Material, RouterLink, UserCard, InfiniteScroll,
      GroupsProfileMembersInvite, GroupsCardUserActionsButton ],
  bindings: [ GroupsService ]
})

export class GroupsProfileMembers {

  group : any;
  session = SessionFactory.build();

  members : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;
  canInvite: boolean = false;

	constructor(public client : Client, public service: GroupsService){

	}

  set _group(value : any){
    this.group = value;
    this.load();
  }

  load(refresh : boolean = false){
    if(this.inProgress)
      return;

    // TODO: [emi] Send this via API
    this.canInvite = false;

    if (this.group.membership == 0 && this.group['is:owner']) {
      this.canInvite = true;
    } else if (this.group.membership == 2 && this.group['is:member']) {
      this.canInvite = true;
    }

    this.inProgress = true;
    this.client.get('api/v1/groups/membership/' + this.group.guid, { limit: 12, offset: this.offset })
      .then((response : any) => {

        if(!response.members){
          this.moreData = false;
          this.inProgress = false;
          return false;
        }

        if(refresh){
          this.members = response.members;
        } else {
          this.members = this.members.concat(response.members);
        }
        this.offset = response['load-next'];
        this.inProgress = false;

      })
      .catch((e)=>{
        this.inProgress = false;
      });
  }

}
