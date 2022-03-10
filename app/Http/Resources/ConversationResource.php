<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $authUserId = $request->user()->id;
        $show_name = ($authUserId == $this->sender_id) ? $this->recipient->fullname : $this->sender->fullname;

        return [
            'id'                    => $this->id,
            'sender_id'             => $this->sender_id,
            'recipient_id'          => $this->recipient_id,
            'sender'                => $this->sender->fullname,
            'recipient'             => $this->recipient->fullname,
            'show_name'             => $show_name,
            'last_message'          => new MessageResource($this->messages()->latest('id')->first()),
            'count_unread_messages' => $this->countConversationUnreadMessages($authUserId),
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }

    private function countConversationUnreadMessages(string $authUserId)
    {
        return Message::where([
            ['conversation_id', $this->id],
            ['sender_id', '<>', $authUserId],
            ['is_read', false]
        ])->count();
    }
}
