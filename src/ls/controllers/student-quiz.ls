require! {
  m: mithril
  r: ramda
  Student: \../models/student
}

class Controller

  ->
    self = @
    @studentId = m.route.param \id
    @performance = []
    Student.cards @studentId .then (data) !->
        {self.cards, self.hints, self.studentName} = data
        self.setContent!

  # record data before window closes
  onunload: (e) ->
    Student.sendStudentResult @performance

  setContent: !->
    | !@cards.length =>
      # TODO eventually relay message to user if fail
      Student.sendStudentResult @performance
      m.route \/app/students
    | _ =>
      card = @cards.shift!
      @content =
        title: @studentName
        cardInfo: card
        hint: (r.find r.propEq \deckId card.deckId) @hints .hint

  # iterate to next card, if finished, then submit to server
  nextCard: (start) ->
    self = @
    @performance.push (
      lapsedTime: Date.now! - start
      cardId: self.content.cardInfo.cardId
    )
    @setContent!

module.exports = Controller
