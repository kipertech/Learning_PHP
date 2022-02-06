<?php

class Question
{
    public int $id;
    public string $content;
    public string $query;
    public string $screenshot_url;

    public function __construct(int $id, string $content, string $query, string $screenshot_url)
    {
        $this->id = $id;
        $this->content = $content;
        $this->query = $query;
        $this->screenshot_url = $screenshot_url;
    }
}

function getQuestions()
{
    $question_array = [];

    array_push($question_array,
        new Question(
            1,
            "What are the ids and names of wombats with weights less than 25 kilos?",
            "SELECT wombat_id, name FROM wombats WHERE weight < 25;",
            "./images/q1.png"
        ),
        new Question(
            2,
            "What's the weight of wombats named Zora?",
            "SELECT name, weight FROM wombats WHERE name = 'Zora';",
            "./images/q2.png"
        ),
        new Question(
            3,
            "What's the weight of wombats named either Zora, or Hawthorne?",
            "SELECT name, weight FROM wombats WHERE name IN ('Zora', 'Hawthorne');",
            "./images/q3.png"
        ),
        new Question(
            4,
            "How many wombats weigh less than 30 kilos?",
            "SELECT Count(*) FROM wombats WHERE weight < 30;",
            "./images/q4.png"
        ),
        new Question(
            5,
            "What are the ids and names of wombats with ids less than 5, or weights more than 40 kilos?",
            "SELECT wombat_id, name FROM wombats WHERE ((wombat_id < 5) OR (weight > 40));",
            "./images/q5.png"
        ),
        new Question(
            6,
            "How many wombats have missing weight data?",
            "SELECT Count(*) AS TotalMissing FROM wombats WHERE weight IS NULL;",
            "./images/q6.png"
        ),
        new Question(
            7,
            "How many wombats have missing data for weights and comments?",
            "SELECT Count(*) AS TotalMissing FROM wombats WHERE ((weight IS NULL) AND (comments IS NULL));",
            "./images/q7.png"
        ),
        new Question(
            8,
            "How many wombats with missing comments have ids less than 10?",
            "SELECT Count(*) AS TotalMissing FROM wombats WHERE ((comments IS NULL) AND (wombat_id < 10));",
            "./images/q8.png"
        )
    );

    return($question_array);
}