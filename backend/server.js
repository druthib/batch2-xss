const express = require("express");
const mongoose = require("mongoose");
const cors = require("cors");

const app = express();
const PORT = process.env.PORT || 5000;

// Middleware
app.use(cors());
app.use(express.json());

// MongoDB Atlas Connection
const uri = "mongodb+srv://bdruthi62:vkds2209***>@cluster0.afis5.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0"; // Replace with your connection string
mongoose.connect(uri, { useNewUrlParser: true, useUnifiedTopology: true })
    .then(() => console.log("Connected to MongoDB Atlas"))
    .catch((err) => console.error("Error connecting to MongoDB Atlas:", err));

// Define a schema for feedback
const feedbackSchema = new mongoose.Schema({
    name: String,
    email: String,
    message: String,
    timestamp: { type: Date, default: Date.now }
});

// Create a model for feedback
const Feedback = mongoose.model("Feedback", feedbackSchema);

// Root route
app.get("/", (req, res) => {
    res.send("Welcome to the Feedback App Backend!");
});

// API to submit feedback
app.post("/api/feedback", async (req, res) => {
    const { name, email, message } = req.body;

    try {
        const feedback = new Feedback({ name, email, message });
        await feedback.save();
        res.status(201).json({ message: "Feedback submitted successfully" });
    } catch (err) {
        res.status(500).json({ error: "Error submitting feedback" });
    }
});

// API to fetch feedback
app.get("/api/feedback", async (req, res) => {
    try {
        const feedback = await Feedback.find().sort({ timestamp: -1 }); // Sort by latest first
        res.status(200).json(feedback);
    } catch (err) {
        res.status(500).json({ error: "Error fetching feedback" });
    }
});

// Start the server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});